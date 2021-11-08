<?php

session_start();

require('vendor/autoload.php');
require "dbh.php";

use Rakit\Validation\Validator;

$validator = new Validator;

if (isset($_POST['create'])) {

    $create = [
        'name' => $_POST['name'],
        'description' => $_POST['desc']
    ];

    $validation = $validator->make($create, [
        'name' => 'required',
        'description' => 'required'
    ]);

    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors();
        $msg = $errors->firstOfAll();
        
        $_SESSION['error'] = $msg;

    } else {
        
        $id = $_SESSION['id'];

        try {

            $sql = "INSERT INTO categories(name, description, user_id, created_at) 
            VALUES(:name, :description, :user_id, current_timestamp())";
            $handler = $db_conn -> prepare($sql);
            $handler -> bindParam(":name", $_POST['name']);
            $handler -> bindParam(":description", $_POST['desc']);
            $handler -> bindParam(":user_id", $id, PDO::PARAM_INT);
            $handler -> execute();

        } catch (PDOException $e) {
            echo $e -> getMessage();
            exit;
        }
    }
}

if (isset($_POST['update'])) {

    $id = $_POST['id'];

    $update = [
        'name' => $_POST['name'],
        'description' => $_POST['desc']
    ];

    $validation = $validator->make($update, [
        'name' => 'required',
        'description' => 'required'
    ]);

    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors();
        $msg = $errors->firstOfAll();
        
        $_SESSION['error'] = $msg;

        header("Location: ../category.php?edit=$id");
        exit;
    } else {
        
        $id = $_POST['id'];

        $sql = "SELECT * FROM categories WHERE id = :id;";
        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(":id", $id);
        $handler -> execute();
        $result = $handler -> fetch();

        if (!empty($result)) {
            try {

                $sql = "UPDATE categories SET name = :name, description = :desc, updated_at = current_timestamp() 
                WHERE id = :id;";
        
                $handler = $db_conn -> prepare($sql);
                $handler -> bindParam(":name", $_POST['name']);
                $handler -> bindParam(":desc", $_POST['desc']);
                $handler -> bindParam(":id", $id, PDO::PARAM_INT);
                $handler -> execute();
                
            } catch (PDOException $e) {
                echo $e -> getMessage();
                exit;
            }
        }

    }
}

if (isset($_POST['delete'])) {
    try {
        
        $sql = "SET FOREIGN_KEY_CHECKS = 0;
        DELETE FROM categories WHERE id = :id;
        SET FOREIGN_KEY_CHECKS = 1;";
        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(":id", $_POST['id'], PDO::PARAM_INT);
        $handler -> execute();

    } catch (PDOException $e) {
        echo $e -> getMessage();
        exit;
    }
}

header("Location: ../category.php");
exit;