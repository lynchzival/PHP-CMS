<?php

session_start();
include "function.php";

$path = $_SERVER['REQUEST_URI'];
$path_split = explode('/', $path);

$isauth = array_search("auth", $path_split) ? true : false;

if (!isset($_SESSION['id'])) {

    if ($isauth) {
        header("Location: ../");
        exit;
    }

} else {
    if (!$isauth) {
        header("Location: auth/");
        exit;
    }
}

?>