<?php

    require "includes/dbh.php";

    $sql = "SELECT * FROM users WHERE id = :id AND id <> :current_id AND role <> 2";   
    $handler = $db_conn -> prepare($sql);
    $handler -> bindParam(':id', $_GET['delete'], PDO::PARAM_INT);
    $handler -> bindParam(':current_id', $_SESSION['id'], PDO::PARAM_INT);

    $handler -> execute();
    $result = $handler -> fetch();

    if (empty($result)) {
        echo "<h1 class='title'>Delete</h1>
        <b class='text-danger text-uppercase'>this action is forbidden for security purpose. 
        <a class='text-primary' href='author.php'>go back</a></b>";
        exit;
    }

?>

<h1 class="title">Delete</h1>

<form action="includes/authorcrud.php" method="POST" id="author">
    <div class="row">
        <div class="col-md-10 mb-3">
            <div class="card m-0 p-4">
                <div class="card-body text-uppercase">
                    Are you sure you want to 
                    <b class="text-danger">delete</b> 
                    <?= $result['role'] == 1 ? "author" : "admin" ?> 
                    <b class="text-primary"><?= $result['name'] ?></b>
                    record?
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <input type="submit" class="btn btn-primary w-100 text-uppercase mb-3" name="delete" value="Delete">
            <input type="hidden" name="id" value="<?= $delete ?>">
            <a class="btn btn-danger w-100 text-uppercase" href="<?= $link ?>">Cancel</a>
        </div>
    </div>

    <?php unset($_SESSION['error']) ?>

</form>