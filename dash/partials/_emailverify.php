<div class="card-body">
    <h3 class="card-title text-center">Email Verification</h3>
    <div class="card-text">

        <?php if(isset($_SESSION["error"]["verify_token"])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert"><?= $_SESSION["error"]["verify_token"] ?></div>
        <?php endif; unset($_SESSION["error"]["verify_token"]);?>

        <form method="GET" class="register-form" id="login-form" action="auth/includes/emailverify.php">
            <div class="form-group">
                <label for="exampleInputEmail1">Verification Token</label>
                <input type="text" class="form-control form-control-sm" name="verify_token" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>

            <button type="submit" class="btn btn-primary btn-block" name="verify">Verify</button>

            <div class="sign-up">
                <button class="btn btn-link" type="submit" name="reset" style="float:right;font-size:12px;">Cancel</button>
                <button class="btn btn-link" type="submit" name="resend" style="float:right;font-size:12px;">Resend</button>
            </div>

        </form>
    </div>
</div>