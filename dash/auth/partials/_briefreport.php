<?php

require "includes/dbh.php";

// date_default_timezone_set('Asia/Phnom_Penh');

$article = $db_conn -> query("SELECT * FROM article WHERE status = 1 ORDER BY id DESC;") -> fetch();
$user = $db_conn -> query("SELECT * FROM users WHERE role = 1 ORDER BY id DESC;") -> fetch();
$category = $db_conn -> query("SELECT * FROM categories ORDER BY id DESC;") -> fetch();

?>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-striped table-bordered w-100" id="brief_table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Articles</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <i class="fas fa-clock"></i>
                    <div class="card-info">
                        <h5>Latest Article</h5>
                    </div>
                </div>
                <div class="p-4 text-center">
                    <a class="text-capitalize" href="../../article.php?id=<?=$article['id']?>"><?= $article['title'] ?></a>
                </div>
                <div class="card-footer">
                    <span><?= date('M j Y g:i A', strtotime($article['created_at'])) ?></span>
                    <span style="float: right;"><?= time_elapsed_str($article['created_at']) ?></span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <i class="fas fa-user"></i>
                    <div class="card-info">
                        <h5>Latest User</h5>
                    </div>
                </div>
                <div class="p-4 text-center">
                    <span class="text-capitalize"><?= empty($user['name']) ? "NULL" : $user['name'] ?></span>
                </div>
                <div class="card-footer">
                    <span><?= empty($user['created_at']) ? "NULL" : date('M j Y g:i A', strtotime($user['created_at'])) ?></span>
                    <span style="float: right;"><?= empty($user['created_at']) ? "NULL" : time_elapsed_str($user['created_at']) ?></span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <i class="fas fa-clock"></i>
                    <div class="card-info">
                        <h5>Latest Category</h5>
                    </div>
                </div>
                <div class="p-4 text-center">
                    <a class="text-capitalize"><?= $category['name'] ?></a>
                </div>
                <div class="card-footer">
                    <span><?= date('M j Y g:i A', strtotime($category['created_at'])) ?></span>
                    <span style="float: right;"><?= time_elapsed_str($category['created_at']) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>