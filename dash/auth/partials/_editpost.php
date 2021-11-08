<?php

require "includes/dbh.php";
$category = "SELECT id, name FROM categories;";
$handler = $db_conn -> query($category);

$article = "SELECT * FROM article WHERE id = :id";
$article_handler = $db_conn -> prepare($article);
$article_handler -> bindParam(":id", $_GET['edit'], PDO::PARAM_INT);
$article_handler -> execute();

$result = $article_handler -> fetch();
$content = str_replace("assets/images/content/", "../../assets/images/content/", $result['content']);

?>

<h1 class="title">Edit</h1>

<form action="includes/articlescrud.php" method="POST" id="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-10">

            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Title</span>
                </div>
                <input type="text" class="form-control" name="title"
                value="<?= $result['title'] ?>">
            </div>
            <span class="error"><?= isset($_SESSION['error']['title']) ? $_SESSION['error']['title'] : "" ?></span>

            <div class="input-group mt-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-image"></i></span>
                </div>
                <div class="custom-file">
                    <input type="file" name="thumb_img" class="custom-file-input" id="file_input">
                    <label class="custom-file-label" for="thumbnail">Choose file</label>
                </div>
            </div>
            <span class="error mb-3"><?= isset($_SESSION['error']['thumb']) ? $_SESSION['error']['thumb'] : "" ?></span>

            <span class="d-block text-uppercase text-center text-info font-weight-bold" id="loader">
                loading editor...
            </span>
            <textarea style="visibility: hidden;" name="content" id="postcontent" 
            cols="30" rows="10"><?= htmlspecialchars($content) ?></textarea>
            <span class="error"><?= isset($_SESSION['error']['content']) ? $_SESSION['error']['content'] : "" ?></span>

        </div>
        <div class="col-lg-2">
            <?php if($result['cover'] != "no_thum_cover.jpg"): ?>
            <div class="mb-4">
                <img src="../../assets/images/content/<?= $result['cover'] ?>" class="img-thumbnail mb-2" alt="">
                <button type="submit" class="btn btn-danger w-100" name="delete_thumbnail">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <?php endif; ?>

            <select class="form-control" name="category">
                <?php while ($row = $handler -> fetch()): ?>
                    <option value="<?= $row['id'] ?>"
                        <?= ($result['category_id'] == $row['id']) ? 'selected' : '' ?> >
                        <?= ucwords($row['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <span class="error"><?= isset($_SESSION['error']['category']) ? $_SESSION['error']['category'] : "" ?></span>

            <div class="custom-control custom-switch mt-3 mb-3">
                <input type="checkbox" class="custom-control-input" id="pin" name="pin"
                <?= ($result['pins'] == 1) ? 'checked' : '' ?>>
                <label class="custom-control-label" for="pin">Pin</label>
            </div>

            <div class="custom-control custom-switch mb-3">
                <input type="checkbox" class="custom-control-input" id="publish" name="status"
                <?= ($result['status'] == 1) ? 'checked' : '' ?>>
                <label class="custom-control-label" for="publish">Status</label>
            </div>
            <input type="hidden" name="id" value="<?= $edit ?>">

            <input type="submit" class="btn btn-primary w-100 text-uppercase mb-3" name="update" value="Update">
            <a class="btn btn-primary w-100 text-uppercase mb-3" 
            href="../../article.php?id=<?= $_GET['edit'] ?>" target="_blank">
                <i class="fas fa-eye"></i>
            </a>
            <a class="btn btn-danger w-100 text-uppercase" href="<?= $link ?>">Cancel</a>
        </div>
    </div>

    <?php unset($_SESSION['error']) ?>

</form>