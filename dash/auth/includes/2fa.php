<?php

session_start();
require "dbh.php";
require "function.php";
require('vendor/autoload.php');
require "sendmail.php";

use Rakit\Validation\Validator;

date_default_timezone_set('Asia/Phnom_Penh');

if (isset($_SESSION['2fa']['verify'])) {

    $info = $_SESSION['2fa'];
    $token = random_int(100000, 999999);

    $url = "$base_url/dash/auth/includes/2fa.php?verify_token=$token&verify";

    // $current_timestamp = time();

    $hash_token = password_hash($token, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO two_factor_auth(user_id, token) VALUES(:user_id, :token)";
        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(":user_id", $info['user_id'], PDO::PARAM_INT);
        $handler -> bindParam(":token", $hash_token);
        $handler -> execute();
    } catch (PDOException $e) {
        echo $e -> getMessage();
        exit;
    }

    $sql = "SELECT * FROM users WHERE id = :id";
    $handler = $db_conn -> prepare($sql);
    $handler -> bindParam(":id", $info['user_id'], PDO::PARAM_INT);
    $handler -> execute();
    $result = $handler -> fetch();

    $content = "Hello {$result['name']},<br><br>
    We recieved a log in request.<br><br>
    The link to verify your login is below:<br><br>
    $url<br><br>
    If this link does not work, go to this page:<br><br>
    $base_url/dash<br><br>
    and enter the 2FA Code '$token' (without the quotes).<br><br>
    If you did not make this request, you can ignore this email.<br><br>
    Thank you,";

    if($msg = sendmail($result['email'], "2FA Verification Code", $content)){
        $_SESSION['error']['verify_token'] = $msg;
    };

    unset($_SESSION['2fa']['verify']);
    $_SESSION['2fa'] += ["confirmed" => true];
    header("Location: ../../");
    exit;
}

if (isset($_GET['verify'])) {

    $validator = new Validator;

    $validation = $validator->make([
        "verify_token" => $_GET["verify_token"]
    ],[
        "verify_token" => "required|numeric"
    ]);

    $validation->validate();

    if ($validation->fails()) {

        $errors = $validation->errors();
        $msg = $errors->firstOfAll();
        
        $_SESSION['error'] = $msg;

        header("Location: ../../");
        exit;
    } else {

        $expired_min = 5;

        $sql = "SELECT user_id, token,
        timestampdiff(MINUTE,current_timestamp(),timestampadd(MINUTE, :minute, created_at)) 'timeStampDiff' 
        FROM two_factor_auth 
        WHERE user_id = :user_id 
        ORDER BY created_at DESC LIMIT 1;";

        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(":minute", $expired_min, PDO::PARAM_INT);
        $handler -> bindParam(":user_id", $_SESSION['2fa']['user_id'], PDO::PARAM_INT);
        $handler -> execute();
        $result = $handler -> fetch();

        if (!empty($result)) {

            if ($result['timeStampDiff'] < 1) {

                $_SESSION['error']['verify_token'] = "Verify expired, click on resend or try again later.";
                header("Location: ../../");
                exit;

            } else {

                $valid = password_verify($_GET['verify_token'], $result['token']);

                if ($valid) {

                    try {
                        $sql = "UPDATE two_factor_auth 
                        SET status = 1 WHERE token = :token AND user_id = :id";

                        $handler = $db_conn -> prepare($sql);
                        $handler -> bindParam(":token", $result['token']);
                        $handler -> bindParam(":id", $result['user_id']);
                        $handler -> execute();
                    } catch (PDOException $e) {
                        echo $e -> getMessage();
                        exit;
                    }

                    $sql = "SELECT * FROM two_factor_auth 
                    WHERE created_at = (SELECT MAX(created_at) FROM two_factor_auth WHERE user_id = :user_id) 
                    AND status = 1";

                    $handler = $db_conn -> prepare($sql);
                    $handler -> bindParam(":user_id", $result['user_id'], PDO::PARAM_INT);
                    $handler -> execute();
                    $result = $handler -> fetch();

                    if (!empty($result)) {
                        $remember = isset($_SESSION['2fa']['remember_me']) ? true : false;
                        unset($_SESSION['2fa']);
                        signin($result['user_id'], "../", $remember);
                    }

                } else {
                    $_SESSION['error']['verify_token'] = "Invalid token, please try again later.";
                    header("Location: ../../");
                    exit;
                }

            }
        } else {
            unset($_SESSION['2fa']);
            header("Location: ../../");
            exit;
        }
    }
}

if (isset($_GET['resend'])) {

    if (!isset($_SESSION['2fa'])) {
        header("Location: ../../");
        exit;
    }

    $info = $_SESSION['2fa'];
    $token = random_int(100000, 999999);

    $link = "$base_url/dash/auth/includes/2fa.php?verify_token=$token&verify";

    $hash_token = password_hash($token, PASSWORD_DEFAULT);

    try {
        $sql = "UPDATE two_factor_auth
        SET created_at = current_timestamp(), token = :token 
        WHERE user_id = :user_id AND created_at = (SELECT MAX(created_at) FROM two_factor_auth WHERE user_id = :select_user_id)";

        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(":token", $hash_token);
        $handler -> bindParam(":user_id", $info['user_id'], PDO::PARAM_INT);
        $handler -> bindParam(":select_user_id", $info['user_id'], PDO::PARAM_INT);
        $handler -> execute();
    } catch (PDOException $e) {
        echo $e -> getMessage();
        exit;
    }

    $sql = "SELECT * FROM users WHERE id = :id";
    $handler = $db_conn -> prepare($sql);
    $handler -> bindParam(":id", $info['user_id'], PDO::PARAM_INT);
    $handler -> execute();
    $result = $handler -> fetch();

    $content = "Hello {$result['name']},<br><br>
    We recieved a log in request.<br><br>
    The link to verify your login is below:<br><br>
    $link<br><br>
    If this link does not work, go to this page:<br><br>
    $base_url/dash<br><br>
    and enter the 2FA Code '$token' (without the quotes).<br><br>
    If you did not make this request, you can ignore this email.<br><br>
    Thank you,";

    if($msg = sendmail($result['email'], "2FA Verification Code", $content)){
        $_SESSION['error']['verify_token'] = $msg;
    };

    header("Location: ../../");
    exit;
}

if (isset($_GET['reset'])) {
    unset($_SESSION['2fa']);
    header("Location: ../../");
    exit;
}

?>