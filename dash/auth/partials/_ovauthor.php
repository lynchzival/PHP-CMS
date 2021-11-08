<?php
    require "includes/dbh.php";
    $sql = "SELECT COUNT(*) Total,
        SUM(CASE role WHEN 1 THEN 1 ELSE 0 END) author,
        SUM(CASE role WHEN 2 THEN 1 ELSE 0 END) admin,
        SUM(CASE WHEN email_verified_at IS NULL THEN 1 ELSE 0 END) unverify
        FROM users;";
    $handler = $db_conn -> query($sql);
    $result = $handler -> fetch();
?>

<div class="row">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <i class="fas fa-user"></i>
                    <div class="card-info">
                        <h5>Total Authors</h5>
                        <h4><?= $result['author'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <i class="fas fa-user-shield"></i>
                    <div class="card-info">
                        <h5>Total Admins</h5>
                        <h4><?= $result['admin'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <i class="fas fa-user-alt-slash"></i>
                    <div class="card-info">
                        <h5>Unverified Accounts</h5>
                        <h4><?= $result['unverify'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>