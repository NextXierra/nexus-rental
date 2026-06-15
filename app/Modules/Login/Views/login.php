<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nexus Rental</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url('vendor/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>">
</head>
<body class="auth-body">

<div class="container">
    <div class="login-wrapper row mx-auto">
        <!-- Form Section -->
        <div class="col-md-6 form-section">
            <a href="<?= base_url('/') ?>" class="close-btn"><i class="fa fa-times"></i></a>
            
            <h3 class="text-center">Nexus Rental</h3>

            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success mt-3"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger mt-3"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')) : ?>
                <div class="alert alert-danger mt-3">
                    <ul class="mb-0 pl-3">
                    <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('login/process') ?>" method="post" class="mt-4">
                <?= csrf_field() ?>
                
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-user-o"></i></span>
                    </div>
                    <input type="text" class="form-control" id="login" name="login" placeholder="Email or Username" value="<?= old('login') ?>" required>
                </div>
                
                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-key"></i></span>
                    </div>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <div class="input-group-append">
                        <span class="input-group-text toggle-password" data-target="#password" style="border-left: none; border-radius: 0 20px 20px 0; cursor: pointer;"><i class="fa fa-eye"></i></span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="rememberMe">
                        <label class="custom-control-label text-muted" for="rememberMe" style="font-size: 0.9rem;">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary">LOGIN</button>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('register') ?>">Register now</a>
                    <a href="#" class="text-muted">Forgot password?</a>
                </div>
            </form>
            
            <div class="divider">or</div>

            <div>
                <a href="#" class="social-btn btn-facebook">
                    <i class="fa fa-facebook"></i> LOGIN WITH FACEBOOK
                </a>
                <a href="#" class="social-btn btn-twitter">
                    <i class="fa fa-twitter"></i> LOGIN WITH TWITTER
                </a>
                <a href="#" class="social-btn btn-google">
                    <i class="fa fa-google"></i> LOGIN WITH GOOGLE
                </a>
            </div>
        </div>
        
        <!-- Image Section -->
        <div class="col-md-6 d-none d-md-block image-section" style="background-image: url('<?= base_url("images/" . $randomGame) ?>');">
            <div class="image-overlay"></div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="<?= base_url('vendor/jquery/jquery.slim.min.js') ?>"></script>
<script src="<?= base_url('vendor/popper/popper.min.js') ?>"></script>
<script src="<?= base_url('vendor/bootstrap/js/bootstrap.min.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var targetSelector = btn.getAttribute('data-target');
            var targetInput = document.querySelector(targetSelector);
            var icon = btn.querySelector('i');
            
            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                targetInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
});
</script>
</body>
</html>
