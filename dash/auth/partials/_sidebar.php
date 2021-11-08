<?php
    $activePage = basename($_SERVER['PHP_SELF'], ".php");
    $profileInfo = getProfileInfo($_SESSION['id']);
?>

<nav id="sidebar" class="<?php echo isset($_SESSION['activeSidebar']) ? "" : "active" ?> d-print-none">
    <div class="sidebar-header">
        <a class="navbar-brand mr-0" href="index.php">
            <img src="img/logo.png" width="50" height="50" class="d-inline-block align-center" alt="">
            <span>WorldVision</span>
        </a>
    </div>

    <ul class="list-unstyled components">
        <li class="<?= ($activePage == 'index') ? 'active':''; ?>">
            <a href="index.php"><i class="fas fa-fw fa-home"></i><span>Dashboard</span></a>
        </li>
        <li class="<?= ($activePage == 'post') ? 'active':''; ?>">
            <a href="post.php"><i class="fas fa-fw fa-pen"></i><span>Post</span></a>
        </li>

        <?php if($profileInfo['role'] == 2): ?>

        <li class="<?= ($activePage == 'category') ? 'active':''; ?>">
            <a href="category.php"><i class="fas fa-fw fa-box-open"></i><span>Category</span></a>
        </li>
        <li class="<?= ($activePage == 'author') ? 'active':''; ?>">
            <a href="author.php"><i class="fas fa-fw fa-users"></i><span>Author</span></a>
        </li>

        <?php endif; ?>
    </ul>
</nav>