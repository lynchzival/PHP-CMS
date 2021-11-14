<?php

session_start();
require "dbh.php";
require "function.php";
require('vendor/autoload.php');
require "sendmail.php";

use Rakit\Validation\Validator;

date_default_timezone_set('Asia/Phnom_Penh');

if (isset($_SESSION['email_verify']['verify']) || isset($_GET['resend'])) {
    
    $id = $_SESSION['email_verify']['user_id'];

    $sql = "SELECT * FROM users WHERE id = :id";
    $handler = $db_conn -> prepare($sql);
    $handler -> bindParam(":id", $id, PDO::PARAM_INT);
    $handler -> execute();
    $result = $handler -> fetch();

    $token = bin2hex(random_bytes(16));

    try {
        $sql = "UPDATE users SET email_verified_at = current_timestamp(), email_verified_token = :token WHERE id = :id;";
        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(":token", $token);
        $handler -> bindParam(":id", $result['id'], PDO::PARAM_INT);
        $handler -> execute();
    } catch (PDOException $e) {
        $e -> getMessage();
        echo $e;
        exit;
    }

    $url = "$base_url/dash/auth/includes/emailverify.php?verify_token=$token&verify";

    $content = "Hello {$result['name']},<br><br>
    We recieved a log in request.<br><br>
    The link to verify your email is below:<br><br>
    $url<br><br>
    If this link does not work, go to this page:<br><br>
    $base_url/dash<br><br>
    and enter the Verify Code '$token' (without the quotes).<br><br>
    If you did not make this request, you can ignore this email.<br><br>
    Thank you,";

    if ($msg = sendmail($result['email'], "Email Verification Code", $content)){
        $_SESSION['error']['verify_token'] = $msg;
    };

    unset($_SESSION['email_verify']['verify']);
    $_SESSION['email_verify'] += ["confirmed" => true];
    header("Location: ../../");
    exit;

}

if (isset($_GET['verify'])) {
    $validator = new Validator;

    $validation = $validator->make([
        "verify_token" => $_GET["verify_token"]
    ],[
        "verify_token" => "required"
    ]);

    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors();
        $msg = $errors->firstOfAll();
        
        $_SESSION['error'] = $msg;

        header("Location: ../../");
        exit;
    } else { 
        $sql = "SELECT *,
        timestampdiff(MINUTE,current_timestamp(),timestampadd(MINUTE, :minute, email_verified_at)) 'timeStampDiff' 
        FROM users WHERE id = :id";

        $expired = 5;

        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(":id", $_SESSION['email_verify']['user_id'], PDO::PARAM_INT);
        $handler -> bindParam(":minute", $expired, PDO::PARAM_INT);
        $handler -> execute();
        $result = $handler -> fetch();

        if (!empty($result)) {

            if ($result['timeStampDiff'] < 1) {
                $_SESSION['error']['verify_token'] = "Verify expired, please try again later.";
                header("Location: ../../");
                exit;
            } else {
                if ($_GET['verify_token'] == $result['email_verified_token']) {
                    try {
                        $sql = "UPDATE users SET email_verified_status = 1 WHERE id = :id";
                        $handler = $db_conn -> prepare($sql);
                        $handler -> bindParam(":id", $result['id'], PDO::PARAM_INT);
                        $handler -> execute();

                        $remember = isset($_SESSION['email_verify']['remember_me']) ? true : false;
                        signin($result['id'], "../", $remember);
                    } catch (PDOException $e) {
                        $e -> getMessage();
                        echo $e;
                        exit;
                    }

                    unset($_SESSION['email_verify']);
                } else {
                    $_SESSION['error']['verify_token'] = "Invalid token, please try again later.";
                    header("Location: ../../");
                    exit;
                }
            }
            
        } else {
            session_destroy();
            header("Location: ../../");
            exit;
        }

    }
}

if (isset($_GET['reset'])) {
    unset($_SESSION['email_verify']);
    header("Location: ../../");
    exit;
}