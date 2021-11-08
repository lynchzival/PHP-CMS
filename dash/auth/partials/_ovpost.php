<?php

    require "includes/dbh.php";

    $sql = "SELECT IFNULL(SUM(IF(pins = 1, 1, 0)), 0) pins,
    IFNULL(SUM(IF(status = 1, 1, 0)), 0) published,
    IFNULL(SUM(IF(status = 0, 1, 0)), 0) unpublished
    FROM article;";
    $handler = $db_conn -> query($sql);
    $result = $handler -> fetch();

?>

<div class="row">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <i class="fas fa-check"></i>
                    <div class="card-info">
                        <h5>Published</h5>
                        <h4><?= $result['published'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <i class="fas fa-times"></i>
                    <div class="card-info">
                        <h5>Unpublished</h5>
                        <h4><?= $result['unpublished'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <i class="fas fa-thumbtack"></i>
                    <div class="card-info">
                        <h5>Total Pins</h5>
                        <h4><?= $result['pins'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>