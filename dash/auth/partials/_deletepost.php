<?php

require "includes/dbh.php";

$sql = "SELECT title FROM article WHERE id = :id";
$handler = $db_conn -> prepare($sql);
$handler -> bindParam(":id", $_GET['delete'], PDO::PARAM_INT);
$handler -> execute();

$result = $handler -> fetch();

?>

<h1 class="title">Delete</h1>

<form action="includes/articlescrud.php" method="POST">
    <div class="row">
        <div class="col-md-10">
            <div class="card m-0 p-4">
                <div class="card-body text-uppercase">
                    Are you sure you want to 
                    <b>delete</b>
                    <b class="text-danger"><?= $result['title'] ?></b>
                    <b>article</b>?
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