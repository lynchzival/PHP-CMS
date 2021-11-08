<?php

$host = "localhost"; // Host name
$username = "root"; // Mysql username
$password = ""; // Mysql password
$db = "vision"; // Database name

$base_url = "http://$_SERVER[HTTP_HOST]";

$addr = "mysql:host=$host;dbname=$db";

$db_conn = new PDO($addr, $username, $password);
$db_conn -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

?>