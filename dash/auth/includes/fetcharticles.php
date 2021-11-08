<?php

    session_start();

    header('Content-Type: application/json; charset=utf-8');

    if (isset($_SESSION['id'])) {
        require "dbh.php";

        // $sql = "SELECT * FROM categories;";
        $sql = "SELECT a.id, a.title, u.name, c.name as cname, a.created_at date, a.status, a.pins
        FROM article a LEFT OUTER JOIN categories c
        ON a.category_id = c.id
        JOIN users u
        ON u.id = a.user_id;";
        
        $handler = $db_conn -> query($sql);

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