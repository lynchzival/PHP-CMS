<?php

    $profileInfo = getProfileInfo($_SESSION['id']);

?>

<nav class="top-nav d-print-none">
    <div class="container-fluid">
        <div class="row align-items-center row-eq-height">

            <div class="col-2 col-sm-1">
                <button type="submit" id="sidebarCollapse" class="btn btn-info mt-0" name="sidebarCollapse">
                    <i class="fas fa-bars"></i>
                </button> 
            </div>

            <!-- <div class="col-8 col-sm-5">
                <form action="#" class="search">
                    <i class="fas fa-search d-none d-sm-inline"></i>
                    <input type="text" class="text-center text-sm-left" name="text" id="text" placeholder="Search...">
                </form>
            </div> -->

            <div class="col-10 col-sm-11" id="collapseComponents">
                <!-- <hr class="d-block d-sm-none"> -->
                <div class="components justify-content-sm-end">

                    <?php if(isset($link) && !isset($_GET['create'])): ?>
                        <a href='<?= $link ?>?create' class='btn btn-primary mr-3' type='button'>
                            <span class='text-uppercase mr-1'>create</span>
                            <i class='fas fa-plus-circle'></i>
                        </a>
                    <?php endif; ?>


                    <button class="btn bg-transparent" type="button" id="ProfileModalBtn" data-toggle="modal" data-target="#ProfileModal">
                        <img class="profile" alt="profile" src="<?= $profileInfo['profile'] ?>">
                    </button>

                    <div class="modal fade" id="ProfileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header d-flex align-items-center">
                                    <img class="profile mr-3" alt="profile" alt="profile" src="<?= $profileInfo['profile'] ?>">
                                    <div class="dropdown-item-content">
                                        <h6><?= ucwords($profileInfo['name']) ?></h6>
                                        <a href="profile.php"><span>See your profile</span></a>
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <a class="dropdown-item d-flex align-items-center py-3" href="#">
                                        <i class="fas fa-lightbulb mr-3"></i>
                                        <div class="dropdown-item-content">
                                            <h6>Give Feedback</h6>
                                            <span>Help us improve our services.</span>
                                        </div>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item d-flex align-items-center py-3">
                                        <i class="fas fa-cog mr-3"></i>
                                        <div class="dropdown-item-content">
                                            <h6>Setting & Privacy</h6>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item d-flex align-items-center py-3">
                                        <i class="fas fa-question mr-3"></i>
                                        <div class="dropdown-item-content">
                                            <h6>Help & Support</h6>
                                        </div>
                                    </a>
                                    <form action="includes/logout.php" method="post">
                                        <button type="submit" name="logout" class="dropdown-item d-flex align-items-center py-3">
                                            <i class="fas fa-sign-out-alt mr-3"></i>
                                            <div class="dropdown-item-content">
                                                <h6>Log Out</h6>
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</nav>