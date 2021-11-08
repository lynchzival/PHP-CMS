
<?php 

    require "includes/startup.php";
    $profileInfo = getProfileInfo($_SESSION['id']);;
    
?>

<!DOCTYPE html>
<html>

    <?php $title = ucwords($profileInfo['name']). " - VisionWorld"; ?>
    <?php include "_meta.php"; ?>

<body>

    <div class="wrapper">

        <!-- Sidebar  -->

        <?php include("partials/_sidebar.php") ?>

        <!-- Page Content  -->

        <div id="content">
            
            <?php 

                include("partials/_nav.php");
                
            ?>

            <div class="main-content">
                <div class="container-fluid">
                    <h1 class="title">Profile</h1>
                        <!-- <div class="col-3">
                            <form action="includes/profileupload.php" method="POST" enctype="multipart/form-data">

                            </form>
                        </div> -->
                    <form action="includes/authorcrud.php" method="POST" enctype="multipart/form-data">

                        <div class="row">

                            <div class="col-md-12 col-lg-3">
                                <img src="<?= $profileInfo['profile'] ?>" alt="Responsive image" class="profile-img-upload">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary" type="submit" name="profile_delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="profile_img" id="file_input">
                                        <label class="custom-file-label" for="file_input">Choose file</label>
                                    </div>
                                </div>
                                <span class="error"><?= isset($_SESSION['error']['profile_img']) ? $_SESSION['error']['profile_img'] : "" ?></span>
                            </div>

                            <div class="col-md-12 col-lg-9">

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">@</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" name="name" value="<?= $profileInfo['name'] ?>">
                                </div>
                                <span class="error"><?= isset($_SESSION['error']['name']) ? $_SESSION['error']['name'] : "" ?></span>

                                <div class="form-group mt-3">
                                    <label for="exampleFormControlInput1">Email address</label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com" name="email" value="<?= $profileInfo['email'] ?>" />
                                    <span class="error"><?= isset($_SESSION['error']['email']) ? $_SESSION['error']['email'] : "" ?></span>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Password</label>
                                            <input type="password" class="form-control" id="exampleFormControlInput1" placeholder="^3%$(123" name="password" />
                                            <span class="error"><?= isset($_SESSION['error']['password']) ? $_SESSION['error']['password'] : "" ?></span>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6">
                                        <div class="form-group mb-4">
                                            <label for="exampleFormControlInput1">Confirmed Password</label>
                                            <input type="password" class="form-control" id="exampleFormControlInput1" placeholder="^3%$(123" name="confirmed_password" />
                                            <span class="error"><?= isset($_SESSION['error']['confirmed_password']) ? $_SESSION['error']['confirmed_password'] : "" ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-4">
                                            <label for="exampleFormControlInput1">Current Password</label>
                                            <input type="password" class="form-control" id="exampleFormControlInput1" placeholder="^3%$(123" name="cur_password" />
                                            <span class="error"><?= isset($_SESSION['error']['current_password']) ? $_SESSION['error']['current_password'] : "" ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <input type="hidden" name="id" value="<?= $profileInfo['id'] ?>">
                                        <input type="submit" class="btn btn-primary w-100 text-uppercase mb-3" name="profile_update" value="Update">
                                        <a class="btn btn-danger w-100 text-uppercase" href="index.php">Cancel</a>
                                    </div>
                                </div>
                            </div>

                            <?php unset($_SESSION['error']) ?>

                        </div>

                    </form>

                </div>
            </div>
                
        </div>
    </div>

<?php require "_script.php" ?>
<script>
    // on pressing enter key click the submit button "profile_update"
    $(document).ready(function() {
        $('input').keypress(function(e) {
            if (e.which == 13) {
                $('input[name="profile_update"]').click();
                return false;
            }
        });
    });
</script>
</body>
</html>