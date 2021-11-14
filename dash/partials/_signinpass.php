<div class="card-body">
    <h3 class="card-title text-center">Set Password</h3>
    <div class="card-text">

        <div class="alert alert-info alert-dismissible fade show" role="alert">
            set a password for <?= $_SESSION["signinpass"]["user_name"] ?>
        </div>

        <form method="POST" class="register-form" id="login-form" action="auth/includes/setpass.php">
            <div class="form-group">
                <label for="exampleInputEmail1">Password</label>
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

            <button type="submit" name="submit" class="btn btn-primary btn-block">Submit</button>

            <div class="sign-up">
                <button class="btn btn-link" type="submit" name="reset" style="float:right;font-size:12px;">Cancel</button>
            </div>
        </form>

        <?php unset($_SESSION["error"]) ?>
    </div>
</div>