<?php

    require "includes/dbh.php";

    $sql = "SELECT (SELECT COUNT(*) FROM article) articles,
    (SELECT COUNT(*) FROM categories) categories,
    (SELECT COUNT(*) FROM users WHERE role = 1) authors;";
    $handler = $db_conn -> query($sql);
    $result = $handler -> fetch();

?>

<div class="row">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <i class="fas fa-pen"></i>
                    <div class="card-info">
                        <h5>Total Articles</h5>
                        <h4><?= $result['articles'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <i class="fas fa-box-open"></i>
                    <div class="card-info">
                        <h5>Total Category</h5>
                        <h4><?= $result['categories'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <i class="fas fa-users"></i>
                    <div class="card-info">
                        <h5>Total Author</h5>
                        <h4><?= $result['authors'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>