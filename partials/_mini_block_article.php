<?php
    require "dash/auth/includes/dbh.php";

    $sql = "SELECT a.id, a.title, u.name, u.profile_img
    FROM article a JOIN users u ON a.user_id = u.id
    WHERE a.status = 1 ORDER BY a.created_at DESC LIMIT 4, 6;";
    $result = $db_conn -> query($sql) -> fetchAll();
?>

<?php if(!empty($result)): $rowCount = 0; ?>

    <?php foreach ($result as $key => $value): ?>
        <?php if ($rowCount % 2 == 0): ?>
        <div class="row">
        <?php endif; ?>
        <?php $rowCount++; ?>
            <?php $profile = getProfileImg($value['name'], "dash/auth/img/", $value['profile_img']); ?>
            <div class="col-sm-6">
                <div class="py-3 border-bottom">
                    <div class="d-flex align-items-center pb-2">
                        <img
                        src="<?= $profile ?>"
                        class="img-xs img-rounded mr-2"
                        alt="thumb"
                        />
                        <span class="fs-12 text-muted"><?= ucwords($value['name']) ?></span>
                    </div>
                    <a href="article.php?id=<?= $value['id'] ?>" class="text-dark">
                        <p class="fs-14 m-0 font-weight-medium line-height-sm" title="<?= $value['title'] ?>">
                            <?= substr($value['title'],0,30) ?>...
                        </p>
                    </a>
                </div>
            </div>
        <?php if ($rowCount % 2 == 0): ?>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>

<?php endif; ?>