<?php

require "includes/dbh.php";
$sql = "SELECT id, name FROM categories;";
$handler = $db_conn -> query($sql);

?>

<h1 class="title">Create</h1>

<form action="includes/articlescrud.php" method="POST" id="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-10">

            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Title</span>
                </div>
                <input type="text" class="form-control" name="title">
            </div>
            <span class="error"><?= isset($_SESSION['error']['title']) ? $_SESSION['error']['title'] : "" ?></span>

            <div class="input-group mt-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-image"></i></span>
                </div>
                <div class="custom-file">
                    <input type="hidden" name="create">
                    <input type="file" name="thumb_img" class="custom-file-input" id="file_input">
                    <label class="custom-file-label" for="thumbnail">Choose file</label>
                </div>
            </div>
            <span class="error mb-3"><?= isset($_SESSION['error']['thumb']) ? $_SESSION['error']['thumb'] : "" ?></span>

            <span class="d-block text-uppercase text-center text-info font-weight-bold" id="loader">
                loading editor...
            </span>
            <textarea style="visibility: hidden;" name="content" id="postcontent" cols="30" rows="10"></textarea>
            <span class="error"><?= isset($_SESSION['error']['content']) ? $_SESSION['error']['content'] : "" ?></span>

        </div>
        <div class="col-lg-2">
            <select class="form-control" name="category">
                <option disabled selected> -- Uncategory -- </option>
                <?php while ($row = $handler -> fetch()): ?>
                    <option value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                <?php endwhile; ?>
            </select>
            <span class="error"><?= isset($_SESSION['error']['category']) ? $_SESSION['error']['category'] : "" ?></span>

            <div class="custom-control custom-switch mt-3 mb-3">
                <input type="checkbox" class="custom-control-input" id="pin" name="pin">
                <label class="custom-control-label" for="pin">Pin</label>
            </div>

            <div class="custom-control custom-switch mb-3">
                <input type="checkbox" class="custom-control-input" checked id="publish" name="status">
                <label class="custom-control-label" for="publish">Status</label>
            </div>
            
            <input type="hidden" name="create" value="true">
            <input type="submit" class="btn btn-primary w-100 text-uppercase mb-3" name="create" value="Create">
            <a class="btn btn-danger w-100 text-uppercase" href="<?= $link ?>">Cancel</a>
        </div>
    </div>

    <?php unset($_SESSION['error']) ?>

</form>