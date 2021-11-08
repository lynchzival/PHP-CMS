<?php

    require "includes/dbh.php";

    $sql = "SELECT * FROM users WHERE id = :id AND id <> :current_id AND role <> 2";   
    $handler = $db_conn -> prepare($sql);
    $handler -> bindParam(':id', $_GET['edit'], PDO::PARAM_INT);
    $handler -> bindParam(':current_id', $_SESSION['id'], PDO::PARAM_INT);

    $handler -> execute();
    $result = $handler -> fetch();

    if (empty($result)) {
        echo "<h1 class='title'>Edit</h1>
        <b class='text-danger text-uppercase'>this action is forbidden for security purpose. 
        <a class='text-primary' href='author.php'>go back</a></b>";
        exit;
    }

?>

<h1 class="title">Edit</h1>

<form action="includes/authorcrud.php" method="POST" id="author">
    <div class="row">
        <div class="col-md-10">
            <div class="form-group">
                <div class="input-group mb-3 form-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">@</span>
                    </div>
                    <input type="text" class="form-control" placeholder="Username" name="name" aria-label="Username" aria-describedby="basic-addon1" value="<?= $result['name'] ?>">
                </div>
                <span class="error"><?= isset($_SESSION['error']['username']) ? $_SESSION['error']['username'] : "" ?></span>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Email address</label>
                        <input type="email" class="form-control" id="exampleFormControlInput1" name="email" placeholder="name@example.com" value="<?= $result['email'] ?>" />
                        <span class="error"><?= isset($_SESSION['error']['email']) ? $_SESSION['error']['email'] : "" ?></span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Role</label>
                        <select class="form-control" name="role">
                            <!-- <option selected disabled></option> -->
                            <option value="1" <?= $result['role'] == 1 ? "selected" : "" ?>>Author</option>
                            <option value="2" <?= $result['role'] == 2 ? "selected" : "" ?>>Admin</option>
                        </select>
                        <span class="error"><?= isset($_SESSION['error']['role']) ? $_SESSION['error']['role'] : "" ?></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Password</label>
                <input type="password" class="form-control" name="password" id="exampleFormControlInput1" placeholder="^3%$(123" />
                <span class="error"><?= isset($_SESSION['error']['password']) ? $_SESSION['error']['password'] : "" ?></span>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Retype Password</label>
                <input type="password" class="form-control" name="retype_pass" id="exampleFormControlInput1" placeholder="^3%$(123" />
                <span class="error"><?= isset($_SESSION['error']['confirmed_password']) ? $_SESSION['error']['confirmed_password'] : "" ?></span>
            </div>
        </div>
        <div class="col-md-2">
            <input type="submit" class="btn btn-primary w-100 text-uppercase mb-3" name="update" value="Update">
            <input type="hidden" name="id" value="<?= $edit ?>">
            <a class="btn btn-danger w-100 text-uppercase" href="<?= $link ?>">Cancel</a>
        </div>
    </div>

    <?php unset($_SESSION['error']) ?>

</form>