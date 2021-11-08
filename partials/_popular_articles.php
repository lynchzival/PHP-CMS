<?php

require "dash/auth/includes/dbh.php";

$sql = "SELECT *, a.created_at as created_at, a.id as id 
FROM article a JOIN categories c ON a.category_id = c.id
WHERE DATE_FORMAT(a.created_at, '%m') = DATE_FORMAT(current_timestamp(), '%m') 
ORDER BY view DESC LIMIT 6;";

$result = $db_conn -> query($sql) -> fetchAll();

?>

<div class="col-lg-9">
<?php if(!empty($result)): ?>
    <?php foreach ($result as $key => $value): ?>
        <?php if ($rowCount % 3 == 0): ?>
        <div class="row mb-3">
        <?php endif; ?>
        <?php $rowCount++; ?>

            <div class="col-sm-4 mb-5 mb-sm-2">
                <a href="article.php?id=<?= $value['id'] ?>">
                    <div class="position-relative image-hover">
                        <img src="assets/images/content/<?= $value['cover'] ?>" 
                        class="img-fluid multi-small-thumb" alt="world-news" />
                        <span class="thumb-title text-uppercase"><?= $value['name'] ?></span>
                    </div>
                </a>
                <h5 class="font-weight-600 mt-3 text-truncate" title="<?= $value['title'] ?>">
                    <?= substr($value['title'],0,50) ?>
                </h5>
                <p class="text-color m-0 d-flex align-items-center">
                    <span class="fs-10 mr-1 text-uppercase"><?= time_elapsed_str($value['created_at']) ?></span>
                    <i class="mdi mdi-clock mr-3"></i>
                    <span class="fs-10 mr-1 text-uppercase"><?= $value['view'] ?></span>
                    <i class="mdi mdi-eye"></i>
                </p>
            </div>

        <?php if ($rowCount % 3 == 0): ?>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
</div>