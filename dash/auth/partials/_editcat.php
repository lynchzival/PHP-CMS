<?php

require "includes/dbh.php";

$sql = "SELECT * FROM categories WHERE id = :id";
$handler = $db_conn -> prepare($sql);
$handler -> bindParam(":id", $_GET['edit']);
$handler -> execute();
$result = $handler -> fetch();

if (!empty($result)) {
    $name = $result['name'];
    $desc = $result['description'];
}

?>

<div class="col-lg-4">
    <form id="catagory" method="POST" action="includes/categoriescrud.php">
        <div class="card bg-light rounded-0 p-4">
            <div class="card-body">
                <h5 class="card-title text-uppercase text-center">Edit</h5>
                <div class="form-group">
                    <label for="catname">Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Sport..."
                    value="<?= isset($name) ? $name : "" ?>">
                    <span class="error"><?= isset($_SESSION['error']['name']) ? $_SESSION['error']['name'] : "" ?></span>
                </div>
                <div class="form-group">
                    <label for="catdesc">Description</label>
                    <textarea class="form-control" name="desc" rows="3"><?= isset($desc) ? $desc : "" ?></textarea>
                    <span class="error"><?= isset($_SESSION['error']['description']) ? $_SESSION['error']['description'] : "" ?></span>
                </div>

                <input type="hidden" name="id" value="<?= $edit ?>">
                <input type="submit" value="update" name="update" class="text-uppercase w-100 btn btn-primary mb-3">
                <a class="btn btn-danger w-100 text-uppercase" href="./category.php">Cancel</a>

            </div>
        </div>
    </form>

    <?php unset($_SESSION['error']) ?>
</div>