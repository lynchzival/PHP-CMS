<?php
    require "dash/auth/includes/dbh.php";
    require "dash/auth/includes/function.php";

    $sql = "SELECT * FROM article WHERE status = 1 ORDER BY created_at DESC LIMIT 4";
    $result = $db_conn -> query($sql) -> fetchAll();
?>

<?php if(!empty($result)): ?>

    <div class="owl-carousel owl-theme" id="main-banner-carousel">
    <?php foreach ($result as $key => $value): ?>
        <div class="item">
            <div class="carousel-content-wrapper mb-2">
            <div class="carousel-content">
                <a href="article.php?id=<?= $value['id'] ?>" class="text-white">
                    <h1 class="font-weight-bold text-white">
                    <?= $value['title'] ?>
                    </h1>
                </a>

                <h5 class="font-weight-normal  m-0">
                <?= substr(strip_tags($value['content']),0,110) ?>
                </h5>
                <p class="text-color m-0 pt-2 d-flex align-items-center">
                
                <span class="fs-10 mr-1 text-uppercase"><?= time_elapsed_str($value['created_at']) ?></span>
                <i class="mdi mdi-clock mr-3"></i>

                <span class="fs-10 mr-1 text-uppercase">
                    <a href="http://<?=$_SERVER['HTTP_HOST']?>/vision/article.php?id=<?= $value['id'] ?>#disqus_thread"
                    class="text-white">0</a>
                </span>
                <i class="mdi mdi-comment-outline mr-3"></i>

                <a href="article.php?id=<?= $value['id'] ?>" class="text-white fs-10 mr-1 text-uppercase">
                    read article
                </a>

                </p>
            </div>
            <div class="carousel-image">
                <div class="overlay"></div>
                <img src="assets/images/content/<?= $value['cover'] ?>" alt="" />
            </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>

<?php endif; ?>