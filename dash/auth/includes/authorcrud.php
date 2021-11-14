<?php

session_start();

require('vendor/autoload.php');
require "dbh.php";
require "sendmail.php";

use Rakit\Validation\Validator;
require "vendor/rakit/validation/src/Rules/UniqueRule.php";

$validator = new Validator;
$validator->addValidator('unique', new UniqueRule($db_conn));

if (isset($_POST['create'])) {

    $create = [
        'username' => $_POST['name'],
        'email' => $_POST['email'],
        'role' => $_POST['role']
    ];

    $validation = $validator->make($create, [
        'username' => 'required',
        'email' => 'required|email|unique:users,email',
        'role' => 'required'
    ]);

    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors();
        $msg = $errors->firstOfAll();
        
        $_SESSION['error'] = $msg;

        header("Location: ../author.php?create");
        exit;
    } else {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $name = $_POST['name'];
        $email = $_POST['email'];
        $hashed_pass = password_hash($randomString, PASSWORD_DEFAULT);
        $role = $_POST['role'];

        try {

            $token = bin2hex(random_bytes(16));

            $sql = "INSERT INTO users(name, email, email_verified_at, email_verified_token, email_verified_status, password, role, created_at)
            VALUES(:name, :email, current_timestamp(), :email_verified_token, 1, :password, :role, current_timestamp())";

            $handler = $db_conn -> prepare($sql);

            $handler -> bindParam(":name", $name);
            $handler -> bindParam(":email", $email);
            $handler -> bindParam(":email_verified_token", $token);
            $handler -> bindParam(":password", $hashed_pass);
            $handler -> bindParam(":role", $role, PDO::PARAM_INT);

            $handler -> execute();

        } catch (PDOException $e) {
            $e -> getMessage();
            echo $e;
            exit;
        }

        $content = "Hello $name,<br><br>
        Your account is finished setting up.<br><br>
        Your email to login is below:<br><br>
        $email<br><br>
        Your password to login is below:<br><br>
        {$randomString}<br><br>
        To log in, go to this page:<br><br>
        $base_url/dash<br><br>
        account verification might require on your first log in.<br><br>
        Thank you,";

        sendmail($email, "Account Credential", $content);

        header("Location: ../author.php");
        exit;

    }

}

if (isset($_POST['update'])) {

    require "function.php";

    $id = $_POST['id'];
    $result = getProfileInfo($id);

    $update = [
        'username' => $_POST['name'],
        'email' => $_POST['email'],
        'role' => $_POST['role'],
        'password' => $_POST['password'],
        'confirmed_password' => $_POST['retype_pass']
    ];

    $validate_rules = [
        'username' => 'required',
        'email' => 'required|email|unique:users,email,'.$result['email'],
        'role' => 'required'
    ];

    if (!empty($_POST['password'])) {
        $validate_rules += [
            'password' => 'required|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/',
            'confirmed_password' => 'required|same:password'
        ];
    }

    $validation = $validator->make($update, $validate_rules);

    $validation->setMessages([
        'regex' => ':attribute must atleast 6 characters long and contains <ul class="text-capitalize"><li>at least one lower case letter</li><li>at least one upper case letter</li><li>at least one digit</li><ul>',
    ]);

    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors();
        $msg = $errors->firstOfAll();
        
        $_SESSION['error'] = $msg;

        header("Location: ../author.php?edit=$id");
        exit;
    } else {

        $password = empty($_POST['password']) ? null : password_hash($_POST['password'], PASSWORD_DEFAULT);
        try {
            $sql = "SET @email = (SELECT email FROM users WHERE id=:select_id);
            UPDATE users
            SET name = :name,
            role = :role,
            updated_at = current_timestamp(),
            email = :email,
            password = COALESCE(:password, password),
            email_verified_status = CASE WHEN email = @email THEN email_verified_status ELSE 0 END
            WHERE id=:update_id;";
            $handler = $db_conn -> prepare($sql);
            $handler -> bindParam(":select_id", $id, PDO::PARAM_INT);
            $handler -> bindParam(":name", $_POST['name']);
            $handler -> bindParam(":role", $_POST['role'], PDO::PARAM_INT);
            $handler -> bindParam(":email", $_POST['email']);
            $handler -> bindParam(":password", $password);
            $handler -> bindParam(":update_id", $id, PDO::PARAM_INT);
            $handler -> execute();
        } catch (PDOException $e) {
            $e -> getMessage();
            echo $e;
            exit;
        }
        
        header("Location: ../author.php");
        exit;
    }
}

if(isset($_POST['delete'])) {

    $id = $_POST['id'];

    try{
        $sql = "SET FOREIGN_KEY_CHECKS = 0;
        DELETE FROM users WHERE id = :id;
        SET FOREIGN_KEY_CHECKS = 1;";
        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(":id", $id, PDO::PARAM_INT);
        $handler -> execute();

    } catch(PDOException $e){
        $e -> getMessage();
        echo $e;
        exit;
    }

    header("Location: ../author.php");
    exit;
    
}

if (isset($_POST['profile_update'])) {

    require "function.php";

    $id = $_POST['id'];
    $result = getProfileInfo($id);

    $update = [
        "name" => $_POST['name'],
        "email" => $_POST['email'],
        "password" => $_POST['password'],
        "confirmed_password" => $_POST['confirmed_password'],
        "current_password" => $_POST['cur_password']
    ];

    $validate_rules = [
        'name' => 'required'
    ];

    if (!empty($_POST['password']) OR $_POST['email'] != $result['email']) {
        $validate_rules += [
            'current_password' => 'required'
        ];

        if($_POST['email'] != $result['email']){
            $validate_rules += [
                'email' => 'required|email|unique:users,email,'.$result['email']
            ];
        }
    
        if (!empty($_POST['password'])) {
            $validate_rules += [
                'password' => 'required|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/',
                'confirmed_password' => 'required|same:password',
            ];
        }
    }

    $validation = $validator->make($update, $validate_rules);

    $validation->setMessages([
        'regex' => ':attribute must atleast 6 characters long and contains <ul class="text-capitalize"><li>at least one lower case letter</li><li>at least one upper case letter</li><li>at least one digit</li><ul>',
    ]);

    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors();
        $msg = $errors->firstOfAll();
        
        $_SESSION['error'] = $msg;

        header("Location: ../profile.php");
        exit;
    } else {

        $dir = "../img/";
        $file = $_FILES['profile_img'];
        $ftype = array(
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'png' => 'image/png'
        );
        $fsize = 5;
        $info = upload_img($file, $ftype, $fsize, $dir);

        if ($info['error']) {
            $_SESSION['error']['profile_img'] = $info["msg"];
            header("Location: ../profile.php");
            exit;
        }

        $profile = isset($info['file_name']) ? $info['file_name'] : null;

        if (!empty($_POST['password']) OR $_POST['email'] != $result['email']) {

            $password = empty($_POST['password']) ? null : password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            if (password_verify($_POST['cur_password'], $result['password'])) {
                try {
                    $sql = "SET @email = (SELECT email FROM users WHERE id=:select_id);
                    UPDATE users
                    SET name = :name,
                    updated_at = current_timestamp(),
                    email = :email,
                    password = COALESCE(:password, password),
                    profile_img = COALESCE(:profile_img, profile_img),
                    email_verified_status = IF(email = @email, email_verified_status, 0)
                    WHERE id=:update_id;";
        
                    $handler = $db_conn -> prepare($sql);
                    $handler -> bindParam(":select_id", $id, PDO::PARAM_INT);
                    $handler -> bindParam(":name", $_POST['name']);
                    $handler -> bindParam(":password", $password);
                    $handler -> bindParam(":profile_img", $profile);
                    $handler -> bindParam(":email", $_POST['email']);
                    $handler -> bindParam(":update_id", $id, PDO::PARAM_INT);
                    $handler -> execute();
                } catch (PDOException $e) {
                    echo $e -> getMessage();
                    exit;
                }

                header("Location: ../profile.php");
                exit;

            } else {

                $_SESSION['error']['current_password'] = "incorrect password";
                header("Location: ../profile.php");
                exit;

            }

        } else {
            try {
                $sql = " UPDATE users
                SET name = :name,
                updated_at = current_timestamp(),
                profile_img = COALESCE(:profile_img, profile_img)
                WHERE id=:update_id;";
                
                $handler = $db_conn -> prepare($sql);
                $handler -> bindParam(":name", $_POST['name']);
                $handler -> bindParam(":profile_img", $profile);
                $handler -> bindParam(":update_id", $id, PDO::PARAM_INT);
                $handler -> execute();
            } catch (PDOException $e) {
                echo $e -> getMessage();
                exit;
            }

            header("Location: ../profile.php");
            exit;
        }

    }

}

if(isset($_POST['profile_delete'])) {

    try {

        $sql = "UPDATE users SET profile_img = default WHERE id = :id";
        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
        $handler -> execute();
        
    } catch (PDOException $e) {
        echo $e -> getMessage();
        exit;
    }

    header("Location: ../profile.php");
    exit;

} 