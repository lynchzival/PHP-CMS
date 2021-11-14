<?php

session_start();

require "dbh.php";
require "function.php";

if(isset($_POST["signin"])){
    $val_email = strtolower(trim($_POST["your_email"]));
    $val_pass = $_POST["your_pass"];
    $error = [];

    if (empty($val_email)) {
        $error["email"] = "email is required";
    } else {
        if (!filter_var($val_email, FILTER_VALIDATE_EMAIL)) {
            $error["email"] = "invalid email format";
        } else {
            $email = $val_email;
        }
    }

    if (empty($val_pass)) {
        $error["pass"] = "password is required";
    } else {
        $pass = $val_pass;
    }

    if (isset($email) && isset($pass)) {

        $sql = "SELECT * FROM users WHERE email=:email;";
        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(':email', $email);
        $handler -> execute();
        $result = $handler -> fetch();

        if (!empty($result)) {

            $password = password_verify($pass, $result["password"]);

            if ($password) {

                if ($result['status'] == 1) {

                    if ($result['email_verified_status'] == 0) {

                        $_SESSION['email_verify'] = [
                            "verify" => true,
                            "user_id" => $result['id']
                        ];
                        header("Location: ../");
                        exit;
                        
                    } else {
                        if ($result['role'] == 1) {
                            $remember = isset($_POST['remember-me']) ? true : false;
                            signin($result['id'], "../", $remember);
    
                        } else {
                            $_SESSION['2fa'] = [
                                "verify" => true,
                                "remember_me" => $_POST['remember-me'],
                                "user_id" => $result['id']
                            ];
                            header("Location: ../");
                            exit;
    
                        }
                    }

                } else {

                    $_SESSION['signinpass'] = [
                        "user_id" => $result['id'],
                        "remember_me" => $_POST['remember-me'],
                        "user_name" => $result['name']
                    ];
                    header("Location: ../");
                    exit;
                }

            } else {

                $error["pass"] = "incorrect password";
                $_SESSION["signin_email"] = $email;
            }
        } else {
            $error["email"] = "incorrect email";
        }

    }

    $_SESSION['error'] = $error;
    header("Location: ../../");
} else {
    header("Location: ../../");
}

?>