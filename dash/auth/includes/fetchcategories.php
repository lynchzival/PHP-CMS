<?php

    session_start();

    header('Content-Type: application/json; charset=utf-8');

    if (isset($_SESSION['id'])) {
        require "dbh.php";

        // $sql = "SELECT * FROM categories;";
        $sql = "SELECT c.id, c.name, c.description, COUNT(a.id) total_article
        FROM categories c
        LEFT JOIN article a
        ON c.id = a.category_id
        GROUP BY c.name;";
        
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