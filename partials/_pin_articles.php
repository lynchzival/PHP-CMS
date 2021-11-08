<?php

require "dash/auth/includes/dbh.php";
$sql = "SELECT * FROM article WHERE pins = 1 AND status = 1 
ORDER BY updated_at DESC LIMIT 3;";
$handler = $db_conn -> query($sql);

if (!empty($db_conn -> query($sql) -> fetch())): ?>

<div class="banner-top-thumb-wrap">
    <div class="d-lg-flex justify-content-between align-items-center">

        <?php while($rows = $handler -> fetch()): ?>
            <a href="article.php?id=<?= $rows['id'] ?>">
                <div class="d-flex justify-content-between mb-3 mb-lg-0">
                    <div>
                        <img src="assets/images/content/<?= $rows['cover'] ?>" alt="thumb" class="banner-top-thumb" />
                    </div>
                    <h5 class="m-0 font-weight-bold text-dark" title="<?= $rows['title'] ?>" >
                        <span class="d-block text-uppercase text-center fs-10">
                            <i class="fas fa-fw fa-thumbtack"></i>
                        </span>
                        <?= substr($rows['title'],0,30) ?>...
                    </h5>
                </div>
            </a>
        <?php endwhile; ?>

    </div>
</div>

<?php endif; ?>