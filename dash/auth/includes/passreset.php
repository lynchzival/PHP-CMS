<?php

session_start();
require "dbh.php";
require "function.php";
require('vendor/autoload.php');
require "sendmail.php";

use Rakit\Validation\Validator;

date_default_timezone_set('Asia/Phnom_Penh');

if (isset($_POST['submit'])) {

    $validator = new Validator;

    $validation = $validator->make([
        "email" => $_POST["email"]
    ],[
        "email" => "required|email"
    ]);

    $validation->validate();

    if ($validation->fails()) {

        $errors = $validation->errors();
        $msg = $errors->firstOfAll();
        
        $_SESSION['error'] = $msg;

        header("Location: ../../forgot.php");
        exit;

    } else {

        if (isset($_SESSION['pass_reset']['token'])) {
            header("Location: ../../forgot.php");
            exit;
        }

        $sql = "SELECT * FROM users WHERE email=:email;";
        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(':email', $_POST['email']);
        $handler -> execute();
        $result = $handler -> fetch();

        if (!empty($result)) {

            $token = bin2hex(random_bytes(16));

            try {

                $sql = "INSERT INTO password_resets VALUES(:email, :token, current_timestamp());";
                $handler = $db_conn -> prepare($sql);
                $handler -> bindParam(":email", $_POST['email']);
                $handler -> bindParam(":token", $token);
                $handler -> execute();
                
            } catch (PDOException $e) {
                echo $e -> getMessage();
                exit;
            }

            $url = "$base_url/dash/auth/includes/passreset.php?reset_token=$token&verify";

            $content = "Hello {$result['name']},<br><br>
            We recieved a password reset request.<br><br>
            The link to reset your password is below:<br><br>
            $url<br><br>
            If you did not make this request, you can ignore this email.<br><br>
            Thank you,";

            if($msg = sendmail($result['email'], "Password Reset", $content)){
                $_SESSION["error"]["generic"] = $msg;
            };

        } else {
            $_SESSION['error']['email'] = "invalid email address";
        }

    }
}

if (isset($_GET['verify'])) {

    $token = isset($_GET['reset_token']) ? $_GET['reset_token'] : null;

    if (is_null($token) || empty($token)) {
        header("Location: ../../");
        exit;    
    } else {

        $expired = 15;

        $sql = "SELECT *, TIMESTAMPDIFF(MINUTE,CURRENT_TIMESTAMP(),TIMESTAMPADD(MINUTE, :minute, created_at)) 'time_stamp_diff' 
        FROM password_resets WHERE token = :token;";
        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(":minute", $expired, PDO::PARAM_INT);
        $handler -> bindParam(":token", $token);
        $handler -> execute();
        $result = $handler -> fetch();

        if (empty($result)) {
            $_SESSION['error']['generic'] = "invalid token";
        } else {
            if ($result['time_stamp_diff'] < 1) {
                $_SESSION['error']['generic'] = "token expired";
            } else {
                $sql = "SELECT name FROM users WHERE email = (SELECT email FROM password_resets WHERE token = :token)";
                $handler = $db_conn -> prepare($sql);
                $handler -> bindParam(":token", $token);
                $handler -> execute();
                $result = $handler -> fetch();

                $_SESSION["pass_reset"]["name"] = "set your new password for username ".$result['name'];
                $_SESSION['pass_reset']["token"] = $token;
            }
        }

    }

}

if (isset($_POST['pass_submit'])) {

    $validator = new Validator;

    $validation = $validator->make([
        'password' => $_POST['password'],
        'confirmed_password' => $_POST['confirmed_password']
    ], [
        'password' => 'required|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/',
        'confirmed_password' => 'required|same:password'
    ]);

    $validation->setMessages([
        'regex' => ':attribute must atleast 6 characters long and contains <ul class="text-capitalize"><li>at least one lower case letter</li><li>at least one upper case letter</li><li>at least one digit</li><ul>',
    ]);

    $validation->validate();

    if ($validation->fails()) {

        $errors = $validation->errors();
        $msg = $errors->firstOfAll();
        $_SESSION['error'] = $msg;

    } else { 
        
        $token = $_SESSION['pass_reset']['token'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {
            $sql = "UPDATE users SET password = :pass, updated_at = current_timestamp()
            WHERE email = (SELECT email FROM password_resets WHERE token = :token)";
            $handler = $db_conn -> prepare($sql);
            $handler -> bindParam(":pass", $password);
            $handler -> bindParam(":token", $token);
            $handler -> execute();
        } catch (PDOException $e) {
            echo $e -> getMessage();
            exit;
        }

        unset($_SESSION['pass_reset']);
        $_SESSION["error"]["generic"] = "your password has been saved";
        header("Location: ../../");
        exit;
        
    }

}

if (isset($_POST['reset'])) {
    unset($_SESSION['pass_reset']);
    header("Location: ../../");
    exit;
}

header("Location: ../../forgot.php");
exit;

?>