<div class="card-body">
    <h3 class="card-title text-center">Sign In</h3>
    <div class="card-text">

        <?php if(isset($_SESSION["error"]["generic"])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert"><?= $_SESSION["error"]["generic"] ?></div>
        <?php endif; unset($_SESSION['error']['generic']); ?>

        <form method="POST" class="register-form" id="login-form" action="auth/includes/login.php">
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" name="your_email" class="form-control form-control-sm" id="exampleInputEmail1" aria-describedby="emailHelp"
                value="<?= isset($_SESSION['signin_email']) ? $_SESSION['signin_email'] : ""; unset($_SESSION['signin_email']) ?>">
                <div class="text-danger">
                    <?= isset($_SESSION["error"]["email"]) ? $_SESSION["error"]["email"] : ""; unset($_SESSION["error"]["email"]) ?>
                </div>
            </div>

            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <a href="./forgot.php" name="forgot" style="float:right;font-size:12px;">Forgot password?</a>
                <input type="password" name="your_pass" class="form-control form-control-sm" id="exampleInputPassword1">
                <div class="text-danger">
                    <?= isset($_SESSION["error"]["pass"]) ? $_SESSION["error"]["pass"] : ""; unset($_SESSION["error"]["pass"]) ?>
                </div>
            </div>

            <button type="submit" name="signin" class="btn btn-primary btn-block">Signin</button>

            <div class="sign-up">
                <div class="form-check">
                    <input class="form-check-input" name="remember-me" type="checkbox" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Remember me
                    </label>
                </div>
            </div>
        </form>
    </div>
</div>