<?php

session_start();

require('vendor/autoload.php');
require "dbh.php";
require "sendmail.php";
require "function.php";

use Rakit\Validation\Validator;
require "vendor/rakit/validation/src/Rules/UniqueRule.php";

$validator = new Validator;
$validator->addValidator('unique', new UniqueRule($db_conn));

if (isset($_POST['create'])) {

    $create = [
        'title' => $_POST['title'],
        'content' => str_replace("../", "", $_POST['content']),
        'category' => $_POST['category']
    ];

    $validation = $validator->make($create, [
        'title' => 'required',
        'content' => 'required',
        'category' => 'required'
    ]);

    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors();
        $msg = $errors->firstOfAll();
        
        $_SESSION['error'] = $msg;

        header("Location: ../post.php?create");
        exit;
    } else {
        
        $sql  = "SELECT AUTO_INCREMENT id
        FROM information_schema.TABLES
        WHERE TABLE_SCHEMA = :db
        AND TABLE_NAME = 'article';";
    
        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(":db", $db);
        $handler -> execute();
        $result = $handler -> fetch();
    
        $id = $result['id'];

        $dir = "../../../assets/images/content/$id/";

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
    
        $file = $_FILES['thumb_img'];
        $ftype = array(
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'png' => 'image/png'
        );
        $fsize = 5;
        
        if (!$info = upload_img($file, $ftype, $fsize, $dir, "thumbnail")) {
            $thumbnail = null;
        } else {
            if (!$info['error']) {
                $thumbnail = str_replace("../../../assets/images/content/", "", $info['file_path']);
            } else {
                $_SESSION['error']['thumb'] = $info['msg'];
                header("Location: ../post.php?create");
                exit;
            }
        }

        $create += [
            'pins' => isset($_POST['pin']) ? 1 : 0,
            'status' => isset($_POST['status']) ? 1 : 0
        ];

        date_default_timezone_set("Asia/Phnom_Penh");
        $slug = strtolower(str_replace(" ", "-", $create['title']))."-".date("d-m-Y");

        try {
            $sql = "INSERT INTO article(title, slug, content, pins, cover, status, user_id, category_id, created_at)
            VALUES(:title, :slug, :content, :pins, IFNULL(:cover, DEFAULT(cover)), 
            :status, :user_id, :category_id, current_timestamp());";

            $handler = $db_conn -> prepare($sql);
            $handler -> bindParam(":title", $create['title']);
            $handler -> bindParam(":slug", $slug);
            $handler -> bindParam(":content", $create['content']);
            $handler -> bindParam(":pins", $create['pins']);
            $handler -> bindParam(":cover", $thumbnail);
            $handler -> bindParam(":status", $create['status'], PDO::PARAM_INT);
            $handler  -> bindParam(":user_id", $_SESSION['id'], PDO::PARAM_INT);
            $handler -> bindParam(":category_id", $create['category'], PDO::PARAM_INT);
            $handler -> execute();

        } catch (PDOException $e) {
            echo $e -> getMessage();
            exit;
        }

    }

}

if(isset($_POST['update'])){

    $id = $_POST['id'];

    $update = [
        'title' => $_POST['title'],
        'content' => str_replace("../", "", $_POST['content']),
        'category' => $_POST['category']
    ];

    $validation = $validator->make($update, [
        'title' => 'required',
        'content' => 'required',
        'category' => 'required'
    ]);

    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors();
        $msg = $errors->firstOfAll();
        
        $_SESSION['error'] = $msg;

        header("Location: ../post.php?edit=$id");
        exit;
    } else { 

        $dir = "../../../assets/images/content/$id/";

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
    
        $file = $_FILES['thumb_img'];
        $ftype = array(
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'png' => 'image/png'
        );
        $fsize = 5;

        if (!$info = upload_img($file, $ftype, $fsize, $dir, "thumbnail")) {
            $thumbnail = null;
        } else {
            if (!$info['error']) {
                $thumbnail = str_replace("../../../assets/images/content/", "", $info['file_path']);
            } else {
                $_SESSION['error']['thumb'] = $info['msg'];
                header("Location: ../post.php?edit=$id");
                exit;
            }
        }

        $update += [
            'pins' => isset($_POST['pin']) ? 1 : 0,
            'status' => isset($_POST['status']) ? 1 : 0,
        ];

        date_default_timezone_set("Asia/Phnom_Penh");
        $slug = strtolower(str_replace(" ", "-", $update['title']))."-".date("d-m-Y");

        try {
            $sql = "UPDATE article
            SET title = :title,
            slug = :slug,
            content = :content,
            pins = :pins,
            cover = COALESCE(:cover_img, cover),
            status = :status,
            category_id = :category_id,
            updated_at = current_timestamp()
            WHERE id = :id";

            $handler = $db_conn -> prepare($sql);

            $handler -> bindParam(":title", $update['title']);
            $handler -> bindParam(":slug", $slug);
            $handler -> bindParam(":content", $update['content']);
            $handler -> bindParam(":pins", $update['pins']);
            $handler -> bindParam(":cover_img", $thumbnail);
            $handler -> bindParam(":status", $update['status']);
            $handler -> bindParam(":category_id", $update['category'], PDO::PARAM_INT);
            $handler -> bindParam(":id", $id, PDO::PARAM_INT);

            $handler -> execute();

        } catch (PDOException $e) {
            echo $e -> getMessage();
            exit;
        }

        header("Location: ../post.php?edit=$id");
        exit;

    }
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    try {
        $sql = "DELETE FROM article WHERE id = :id";
        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(":id", $id, PDO::PARAM_INT);
        $handler -> execute();
    } catch (PDOException $e) {
        echo $e -> getMessage();
        exit;
    }
}

if (isset($_POST['delete_thumbnail'])) {

    $id = $_POST['id'];

    try {
        $sql = "UPDATE article SET cover = default WHERE id = :id";
        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(':id', $id, PDO::PARAM_INT);
        $handler -> execute();
    } catch (PDOException $e) {
        echo $e -> getMessage();
        exit;
    }

    header("Location: ../post.php?edit=$id");
    exit;
}

if (isset($_POST['img_insert'])) {

    if (isset($_POST['new'])) {
        $sql  = "SELECT AUTO_INCREMENT id
        FROM information_schema.TABLES
        WHERE TABLE_SCHEMA = :db
        AND TABLE_NAME = 'article';";
    
        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(":db", $db);
        $handler -> execute();
        $result = $handler -> fetch();
    
        $id = $result['id'];
    } else if (isset($_POST['edit'])) {
        $id = $_POST['edit'];
    }

    $dir = "../../../assets/images/content/$id/";

    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }

    reset($_FILES);
    $file = current($_FILES);
    $ftype = array(
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'png' => 'image/png'
    );
    $fsize = 5;
    $info = upload_img($file, $ftype, $fsize, $dir);

    if (!$info['error']) {
        echo json_encode(array('location' => preg_replace("(../)", "", $info['file_path'], 1) ));
        exit;
    } else {
        header("HTTP/1.1 400.");
        echo json_encode(array('error' => $info['msg'] ));
        return;
    }

}

header("Location: ../post.php");
exit;


?>