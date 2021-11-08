<?php

    session_start();

    header('Content-Type: application/json; charset=utf-8');

    if (isset($_SESSION['id'])) {
        require "dbh.php";

        $sql = "SELECT id, name, email, date(created_at) created_at, 
        IF(status=1, 'verified', 'unverified') status, 
        IF(role=1, 'author', 'admin') roles
        FROM users WHERE id <> :id";

        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
        $handler -> execute();

        $array = [];

        while($row = $handler -> fetch()) {
            $array[] = $row;
        }

        $dataset = array(
            "totalrecords" => count($array),
            "data" => $array
        );

        echo json_encode($dataset);
        
    }

?>