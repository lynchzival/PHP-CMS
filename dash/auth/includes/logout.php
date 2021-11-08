<?php

session_start();

if (isset($_POST['logout'])) {

    if (isset($_COOKIE['clogin'])) {
        setcookie("clogin", null, -1, "/vision/");
    }

    unset($_SESSION['id']);
    session_destroy();
}

header("Location: ../index.php");

?>