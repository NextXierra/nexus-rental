<?php
$games = [
    'pes2019.jpg', 'fifa2019.jpg', 'spiderman.png', 'pes2018.jpg', 'ps4fifa18.jpg', 
    'god-of-war-4-day-one-edition.jpg', 'Grand-Theft-Auto-V-PS4-Box-Art.jpg', 
    'capcom-2-1003801.jpg', 'nsuns_legacy_ps4_3d_pegi_usk_1499157554.jpg', 
    '81HNE-+Y6WL._SL1500_.jpg', 'injustice.jpg', 'ps4-the-witcher-3-wild-hunt_1.jpg', 
    '81Oqvv6OxHL._SL1500_.jpg', '91iH-qAxe7L._SL1500_.jpg', 'Horizon-zero-dawn-box-art.jpg', 
    'ufc_2_PS4_1_front_fvlb_3602794547772389271.jpg', 'C0R4myqWgAERMF2.jpg', '1390942.jpg'
];
$randomGame = $games[array_rand($games)];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Nexus Rental</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?= old('username') ?>" required>
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-envelope-o"></i></span>
                    </div>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email address" value="<?= old('email') ?>" required>
                </div>
                
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    </div>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>

                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    </div>
                    <input type="password" class="form-control" id="pass_confirm" name="pass_confirm" placeholder="Confirm Password" required>
                </div>

                <button type="submit" class="btn btn-success">REGISTER</button>

                <div class="text-center mt-4">
                    <span class="text-muted" style="font-size: 0.9rem;">Already have an account?</span> <a href="<?= base_url('login') ?>">Login here</a>
                </div>
            </form>
        </div>
        
        <!-- Image Section -->
        <div class="col-md-6 d-none d-md-block image-section" style="background-image: url('<?= base_url("images/" . $randomGame) ?>');">
            <div class="image-overlay"></div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('vendor/popper/popper.min.js') ?>"></script>
<script src="<?= base_url('vendor/bootstrap/js/bootstrap.min.js') ?>"></script>
</body>
</html>
