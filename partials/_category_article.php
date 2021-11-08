<?php

    require "dash/auth/includes/function.php";

    function get_recent_articles($cat_id, $offset, $lenth){
        require "dash/auth/includes/dbh.php";

        $sql = "SELECT *, a.id as id, a.created_at as created_at
        FROM article a JOIN categories c ON a.category_id = c.id 
        WHERE status = 1 AND category_id = :id
        ORDER BY a.created_at DESC LIMIT :offset, :length;";

        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(":id", $cat_id, PDO::PARAM_INT);
        $handler -> bindParam(":offset", $offset, PDO::PARAM_INT);
        $handler -> bindParam(":length", $lenth, PDO::PARAM_INT);
        $handler -> execute();
        $result = $handler->fetchAll();

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

?>

<?php if($article = get_recent_articles($_GET['id'], 0, 1)): ?>

<div class="col-lg-6 mb-5 mb-sm-2">
    <a href="article.php?id=<?= $article[0]['id'] ?>">
        <div class="position-relative image-hover">
            <img src="assets/images/content/<?= $article[0]['cover'] ?>" 
            class="img-fluid big-thumb" alt="world-news" />
            <span class="thumb-title text-uppercase"><?= $article[0]['name'] ?></span>
        </div>
    </a>
    <h1 class="font-weight-600 mt-3">
        <?= $article[0]['title'] ?>
    </h1>
    <p class="fs-15 font-weight-normal">
        <?= substr(strip_tags($article[0]['content']),0,110) ?>...
    </p>
    <p class="text-color m-0 d-flex align-items-center">
        <span class="fs-10 mr-1 text-uppercase"><?= time_elapsed_str($article[0]['created_at']) ?></span>
        <i class="mdi mdi-clock mr-3"></i>
        <a href="article.php?id=<?= $article[0]['id'] ?>" class="fs-10 mr-1 text-uppercase text-dark">Read Article</a>
    </p>
</div>

<?php endif; ?>

<div class="col-lg-6 mb-5 mb-sm-2">
    <?php if($article = get_recent_articles($_GET['id'], 1, 4)): $rowCount = 0; ?>

        <?php foreach ($article as $key => $row): ?>
            <?php if ($rowCount % 2 == 0): ?>
            <div class="row">
            <?php endif; ?>
            <?php $rowCount++; ?>

                <div class="col-sm-6 mb-5 mb-sm-2">
                    <a href="article.php?id=<?= $article[$key]['id'] ?>">
                        <div class="position-relative image-hover">
                            <img src="assets/images/content/<?= $article[$key]['cover'] ?>" 
                            class="img-fluid small-thumb" alt="world-news" />
                            <span class="thumb-title text-uppercase"><?= $article[$key]['name'] ?></span>
                        </div>
                    </a>
                    <h5 class="font-weight-600 mt-3 text-truncate" title="<?= $article[$key]['title'] ?>">
                        <?= $article[$key]['title'] ?>
                    </h5>
                    <p class="fs-15 font-weight-normal">
                        <?= substr(strip_tags($article[$key]['content']),0,50) ?>...
                    </p>
                    <p class="text-color m-0 d-flex align-items-center">
                        <span class="fs-10 mr-1 text-uppercase"><?= time_elapsed_str($article[$key]['created_at']) ?></span>
                        <i class="mdi mdi-clock mr-3"></i>
                        <a href="article.php?id=<?= $article[$key]['id'] ?>" 
                        class="fs-10 mr-1 text-uppercase text-dark">Read Article</a>
                    </p>
                </div>

            <?php if ($rowCount % 2 == 0): ?>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>

    <?php endif; ?>
</div>

<div class="col-lg-12 mt-0 mt-md-5">

<?php

    $rec_per_page = 8;

    $count = "SELECT COUNT(*) total FROM article WHERE status = 1 AND category_id = :cat_id";
    $count_handler = $db_conn -> prepare($count);
    $count_handler -> bindParam(":cat_id", $_GET['id'], PDO::PARAM_INT);
    $count_handler -> execute();
    $count_result = $count_handler -> fetch();

    $total_rec = $count_result['total']-5;
    $last_page = ceil($total_rec/$rec_per_page);

    $current_page = isset($_GET["page"]) ? (is_numeric($_GET["page"]) ? $_GET["page"] : 1 ) : 1;
    $current_page = $current_page < 1 ? 1 : ($current_page > $last_page ? $last_page : $current_page);

    $offset = ($current_page-1)*$rec_per_page;
    $offset = $offset < 0 ? 0 : $offset;
  
?>

<?php if($article = get_recent_articles($_GET['id'], $offset+5, $rec_per_page)): $rowCount = 0; ?>
    <?php foreach ($article as $key => $row): ?>
        <?php if ($rowCount % 4 == 0): ?>
        <div class="row mb-4">
        <?php endif; ?>
        <?php $rowCount++; ?>

            <div class="col-md-6 col-lg-3 mb-5 mb-sm-2">
                <a href="article.php?id=<?= $article[$key]['id'] ?>">
                    <div class="position-relative image-hover">
                        <img src="assets/images/content/<?= $article[$key]['cover'] ?>" 
                        class="img-fluid multi-small-thumb" alt="world-news" />
                        <span class="thumb-title text-uppercase"><?= $article[$key]['name'] ?></span>
                    </div>
                </a>
                <h5 class="font-weight-600 mt-3 text-truncate" title="<?= $article[$key]['title'] ?>">
                    <?= substr($article[$key]['title'],0,50) ?>
                </h5>
                <p class="text-color m-0 d-flex align-items-center">
                    <span class="fs-10 mr-1 text-uppercase"><?= time_elapsed_str($article[$key]['created_at']) ?></span>
                    <i class="mdi mdi-clock mr-3"></i>
                    <a href="article.php?id=<?= $article[$key]['id'] ?>" 
                    class="fs-10 mr-1 text-uppercase text-dark">Read Article</a>
                </p>
            </div>

        <?php if ($rowCount % 4 == 0): ?>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

</div>

<div class="row w-100">
    <div class="d-flex justify-content-center col-12">
        <?php pagination($current_page, $total_rec, $rec_per_page, 2, "category.php?id={$_GET['id']}&page=%d") ?>
    </div>
</div>