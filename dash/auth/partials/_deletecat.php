<?php

    require "includes/dbh.php";

    $sql = "SELECT id, name FROM categories WHERE id = :id";
    $handler = $db_conn -> prepare($sql);
    $handler -> bindParam(":id", $_GET['delete']);
    $handler -> execute();
    $result = $handler -> fetch();

?>

<?php if (!empty($result)): $id = $result['id']; $name = $result['name']; ?>
    <div class="col-lg-4">
        <form action="includes/categoriescrud.php" method="POST" id="author">
            <div class="card bg-light rounded-0 p-4">
                <div class="card-body text-uppercase mb-3">
                    <h5 class="card-title text-uppercase text-center">Delete</h5>
                    Are you sure you want to
                    <b class="text-danger">delete</b> 
                    category <?= isset($id) ? $id : "" ?>
                    <b class="text-primary"><?= isset($name) ? $name : "" ?></b>?
                </div>

                <input type="hidden" name="id" value="<?= $delete ?>">
                <input type="submit" class="btn btn-primary w-100 text-uppercase mb-3" name="delete" value="Delete">
                <a class="btn btn-danger w-100 text-uppercase" href="./category.php">Cancel</a>
            </div>

            <?php unset($_SESSION['error']) ?>

        </form>
    </div>
<?php endif; ?>