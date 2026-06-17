<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Nexus Rental</title>
    <link class="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url('vendor/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">
</head>
<body class="auth-body">

<div class="container">
    <div class="login-wrapper row mx-auto">
        <div class="col-md-6 form-section">
            <a href="<?= base_url('/') ?>" class="close-btn"><i class="fa fa-times"></i></a>
            
            <h3 class="text-center">Nexus Rental</h3>

            <?php if (session()->getFlashdata('errors')) : ?>
                <div class="alert alert-danger mt-3">
                    <ul class="mb-0 pl-3">
                    <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('register/process') ?>" method="post" class="mt-4">
                <?= csrf_field() ?>
                
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                    </div>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" value="<?= old('nama') ?>" required>
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-envelope-o"></i></span>
                    </div>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email address" value="<?= old('email') ?>" required>
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                    </div>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="No. Handphone / WA" value="<?= old('no_hp') ?>">
                </div>
                
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    </div>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <div class="input-group-append">
                        <span class="input-group-text toggle-password" data-target="#password" style="border-left: none; border-radius: 0 20px 20px 0; cursor: pointer;"><i class="fa fa-eye"></i></span>
                    </div>
                </div>

                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    </div>
                    <input type="password" class="form-control" id="pass_confirm" name="pass_confirm" placeholder="Confirm Password" required>
                    <div class="input-group-append">
                        <span class="input-group-text toggle-password" data-target="#pass_confirm" style="border-left: none; border-radius: 0 20px 20px 0; cursor: pointer;"><i class="fa fa-eye"></i></span>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">REGISTER</button>

                <div class="text-center mt-4">
                    <span class="text-muted" style="font-size: 0.9rem;">Already have an account?</span> <a href="<?= base_url('login') ?>">Login here</a>
                </div>
            </form>
        </div>
        
        <div class="col-md-6 d-none d-md-block image-section" style="background-image: url('<?= base_url("images/" . $randomGame) ?>');">
            <div class="image-overlay"></div>
        </div>
    </div>
</div>

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
