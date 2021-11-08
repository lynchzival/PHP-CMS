<div class="card-body">
    <h3 class="card-title text-center">Password Reset</h3>
    <div class="card-text">

        <?php if(isset($_SESSION["pass_reset"]["name"])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert"><?= $_SESSION["pass_reset"]["name"] ?></div>
        <?php endif;?>

        <form method="POST" class="register-form" id="login-form" action="auth/includes/passreset.php">
            <div class="form-group">
                <label for="exampleInputEmail1">New Password</label>
                <input type="password" name="password" class="form-control form-control-sm">
                <div class="text-danger">
                    <?= isset($_SESSION["error"]["password"]) ? $_SESSION["error"]["password"] : ""; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="exampleInputEmail1">Confirmed Password</label>
                <input type="password" name="confirmed_password" class="form-control form-control-sm">
                <div class="text-danger">
                    <?= isset($_SESSION["error"]["confirmed_password"]) ? $_SESSION["error"]["confirmed_password"] : ""; ?>
                </div>
            </div>

            <button type="submit" name="pass_submit" class="btn btn-primary btn-block">Reset</button>

            <div class="sign-up">
                <button class="btn btn-link" type="submit" name="reset" style="float:right;font-size:12px;">Cancel</button>
            </div>
        </form>

        <?php unset($_SESSION["error"]) ?>
    </div>
</div>