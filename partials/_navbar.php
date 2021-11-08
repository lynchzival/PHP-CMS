<?php 
    
    date_default_timezone_set('Asia/Phnom_Penh');

    require "dash/auth/includes/dbh.php";
    $sql = "SELECT * FROM categories;";
    $handler = $db_conn -> query($sql);
    
?>

<nav class="navbar navbar-expand-lg navbar-light">
    <div class="d-flex justify-content-between align-items-center navbar-top">
        <ul class="navbar-left">
            <li><?= date("l jS F Y") ?></li>
        </ul>
        <div>
            <a class="navbar-brand" href="./"><img src="assets/images/logo.svg" alt="" /></a>
        </div>
        <div class="d-flex">
            <ul class="social-media">
                <li>
                    <a href="#">
                        <i class="mdi mdi-instagram"></i>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="mdi mdi-facebook"></i>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="mdi mdi-youtube"></i>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="mdi mdi-linkedin"></i>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="mdi mdi-twitter"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="navbar-bottom-menu">
        <button class="navbar-toggler" type="button" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse justify-content-center collapse" id="navbarSupportedContent">
            <ul class="navbar-nav d-lg-flex justify-content-between align-items-center">
                <li>
                    <button class="navbar-close">
                        <i class="mdi mdi-close"></i>
                    </button>
                </li>
                <li class="nav-item active">
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <?php while($rows = $handler -> fetch()): ?>
                
                    <li class="nav-item active">
                        <a class="nav-link active" href="category.php?id=<?= $rows['id'] ?>">
                            <?= ucwords($rows['name']) ?>
                        </a>
                    </li>

                <?php endwhile; ?>
                <li class="nav-item">
                    <a class="nav-link" href="search.php"><i class="mdi mdi-magnify"></i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>