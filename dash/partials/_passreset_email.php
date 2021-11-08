<div class="card-body">
    <h3 class="card-title text-center">Password Reset</h3>
    <div class="card-text">

        <?php if(isset($_SESSION["error"]["generic"])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert"><?= $_SESSION["error"]["generic"] ?></div>
        <?php endif;?>

        <form method="POST" class="register-form" id="login-form" action="auth/includes/passreset.php">
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" name="email" class="form-control form-control-sm" id="exampleInputEmail1" aria-describedby="emailHelp">
                <div class="text-danger">
                    <?= isset($_SESSION["error"]["email"]) ? $_SESSION["error"]["email"] : ""; ?>
                </div>
            </div>

            <button type="submit" name="submit" class="btn btn-primary btn-block">Reset</button>
        </form>

        <div class="sign-up">
            <a href="." class="btn btn-link" type="submit" style="float:right;font-size:12px;">Signin</a>
        </div>

        <?php unset($_SESSION["error"]) ?>
    </div>
</div>