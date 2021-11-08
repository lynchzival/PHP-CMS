<?php

require "dash/auth/includes/dbh.php";

$sql = "SELECT * FROM article a
WHERE DATE_FORMAT(created_at, '%m') = DATE_FORMAT(current_timestamp(), '%m')-1
ORDER BY view DESC LIMIT 4;";

$result = $db_conn -> query($sql) -> fetchAll();

?>

<div class="col-lg-3">

    <?php include "partials/_sidebar_ads.php"; ?>

    <?php if(!empty($result)): ?>

    <div class="row">

        <div class="col-sm-12">
            <div class="d-flex position-relative float-left">
                <h3 class="section-title">Previous Month</h3>
            </div>
        </div>

        <?php foreach($result as $row): ?>

        <div class="col-sm-12">
            <div class="border-bottom pb-3">
                <a href="article.php?id=<?= $row['id'] ?>" class="text-dark">
                    <h5 class="font-weight-600 mt-0 mb-0">
                        <?= $row['title'] ?>
                    </h5>
                </a>
                <p class="text-color m-0 d-flex align-items-center">
                    <span class="fs-10 mr-1 text-uppercase"><?= time_elapsed_str($row['created_at']) ?></span>
                    <i class="mdi mdi-clock mr-3"></i>
                    <span class="fs-10 mr-1"><?= $row['view'] ?></span>
                    <i class="mdi mdi-eye"></i>
                </p>
            </div>
        </div>

        <?php endforeach; ?>

    <?php endif; ?>

    </div>
</div>