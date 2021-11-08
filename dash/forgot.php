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
    <title>Password Reset</title>
    <link rel="stylesheet" href="auth/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="global-container">
    <div class="card login-form">
        <?php
            if (isset($_SESSION['pass_reset']['token'])) {
                include "partials/_passreset_pass.php";
            } else {
                include "partials/_passreset_email.php";
                exit;
            }
        ?>
    </div>
</div>
</body>
</html>