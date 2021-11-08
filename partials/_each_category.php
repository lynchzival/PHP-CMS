<?php

require "dash/auth/includes/dbh.php";

$category = "SELECT * FROM categories;";
$cat_result = $db_conn -> query($category) -> fetchAll();

?>

<?php foreach ($cat_result as $key => $value): ?>
    <div class="row mt-3">
        <div class="col-sm-12">
            <div class="d-flex position-relative float-left">
                <a href="category.php?id=<?= $value['id'] ?>">
                    <h3 class="section-title text-capitalize"><?= $value['name'] ?></h3>
                </a>
            </div>
        </div>
    </div>

    <?php
        $article = "SELECT * FROM article WHERE category_id = :cat_id AND status = 1 ORDER BY created_at DESC LIMIT 4;";
        $handler = $db_conn -> prepare($article);
        $handler -> bindParam(":cat_id", $value['id'], PDO::PARAM_INT);
        $handler -> execute();
        $a_result = $handler -> fetchAll();
    ?>

    <div class="row mb-5">
    <?php foreach ($a_result as $key => $a_val): ?>
        <div class="col-md-6 col-lg-3 grid-margin mb-5 mb-sm-2">
            <a href="article.php?id=<?= $a_val['id'] ?>">
                <div class="position-relative image-hover">
                    <img src="assets/images/content/<?= $a_val['cover'] ?>" 
                    class="img-fluid img-cat-thumb" alt="world-news" />
                    <span class="thumb-title text-uppercase"><?= $value['name'] ?></span>
                </div>
            </a>
            <h5 class="font-weight-bold mt-3 text-truncate" title="<?= $a_val['title'] ?>">
                <?= substr($a_val['title'],0,30) ?>
            </h5>
            <p class="fs-15 font-weight-normal text-truncate">
                <?= substr(strip_tags($a_val['content']),0,70) ?>...
            </p>
            <p class="text-color m-0 d-flex align-items-center">
                <span class="fs-10 mr-1 text-uppercase"><?= time_elapsed_str($a_val['created_at']) ?></span>
                <i class="mdi mdi-clock mr-3"></i>
                <a href="article.php?id=<?= $a_val['id'] ?>" class="fs-10 mr-1 text-uppercase text-dark">Read Article</a>
            </p>
        </div>
    <?php endforeach; ?>
    </div>

<?php endforeach; ?>
