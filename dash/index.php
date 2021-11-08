<?php 
    require "auth/includes/startup.php";
    checklogin("auth");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="auth/img/logo.png" type="image/x-icon">
    <title>Sign in</title>
    <link rel="stylesheet" href="auth/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="global-container">
    <div class="card login-form">
        <?php
            if (isset($_SESSION['2fa'])) {

                if (isset($_SESSION['2fa']['confirmed'])) {
                    include "partials/_2FA.php";
                } else {
                    header("Location: auth/includes/2fa.php");
                    exit;
                }
                
            } else if (isset($_SESSION['email_verify'])) {

                if (isset($_SESSION['email_verify']['confirmed'])) {
                    include "partials/_emailverify.php";
                } else {
                    header("Location: auth/includes/emailverify.php");
                    exit;
                }
                
            } else {
                include "partials/_signin.php";
            }
        ?>
    </div>
</div>
</body>
</html>