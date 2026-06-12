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
    <title>Register - Royal Rental</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url('vendor/bootstrap/css/bootstrap.min.css') ?>">
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: Lora, "Helvetica Neue", Helvetica, Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-wrapper {
            background-color: #111;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            max-width: 900px;
            width: 100%;
        }
        .form-section {
            padding: 3rem;
            background-color: #111;
            position: relative;
        }
        .form-section h3 {
            font-family: Cabin, "Helvetica Neue", Helvetica, Arial, sans-serif;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 2rem;
            color: #fff;
        }
        .close-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
            text-decoration: none;
        }
        .close-btn:hover {
            color: #E8890A;
        }
        .input-group-text {
            background-color: #222;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-right: none;
            color: #888;
            border-radius: 20px 0 0 20px;
        }
        .form-control {
            background-color: #222;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-left: none;
            color: #fff;
            border-radius: 0 20px 20px 0;
            padding-left: 0;
        }
        .form-control:focus {
            background-color: #222;
            border-color: #E8890A;
            color: #fff;
            box-shadow: none;
        }
        .form-control:focus + .input-group-text {
            border-color: #E8890A;
        }
        .btn-success {
            background-color: #E8890A;
            border-color: #E8890A;
            border-radius: 20px;
            color: #000;
            font-weight: bold;
            padding: 0.5rem 2rem;
            width: 100%;
            margin-top: 1rem;
        }
        .btn-success:hover {
            background-color: #c77608;
            border-color: #c77608;
            color: #000;
        }
        a {
            color: #E8890A;
            font-size: 0.9rem;
        }
        a:hover {
            color: #c77608;
            text-decoration: none;
        }
        .image-section {
            background-image: url('<?= base_url("images/" . $randomGame) ?>');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, #111, transparent);
        }
    </style>
</head>
<body>

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
        <div class="col-md-6 d-none d-md-block image-section">
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
