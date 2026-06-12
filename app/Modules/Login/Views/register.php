<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Royal Rental</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('vendor/bootstrap/css/bootstrap.min.css') ?>">
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: Lora, "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        .card {
            background-color: #111;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
        }
        .card-body {
            padding: 2rem;
        }
        .form-control {
            background-color: #222;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            border-radius: 10px;
        }
        .form-control:focus {
            background-color: #333;
            border-color: #E8890A;
            color: #fff;
            box-shadow: none;
        }
        .btn-success {
            background-color: #E8890A;
            border-color: #E8890A;
            border-radius: 10px;
            color: #000;
            font-weight: bold;
        }
        .btn-success:hover {
            background-color: #c77608;
            border-color: #c77608;
            color: #000;
        }
        a {
            color: #E8890A;
        }
        a:hover {
            color: #c77608;
            text-decoration: none;
        }
        .text-center h3 {
            font-family: Cabin, "Helvetica Neue", Helvetica, Arial, sans-serif;
            text-transform: uppercase;
            letter-spacing: .05em;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <h2 style="font-family: Cabin, sans-serif; text-transform: uppercase; letter-spacing: 0.1em;"><a href="<?= base_url('/') ?>" style="color: #fff;">Nexus Rental</a></h2>
            </div>
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="text-center mb-4">Register</h3>

                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('register/process') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?= old('username') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="pass_confirm">Confirm Password</label>
                            <input type="password" class="form-control" id="pass_confirm" name="pass_confirm" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Register</button>
                    </form>

                    <div class="mt-3 text-center">
                        <p>Already have an account? <a href="<?= base_url('login') ?>">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('vendor/popper/popper.min.js') ?>"></script>
<script src="<?= base_url('vendor/bootstrap/js/bootstrap.min.js') ?>"></script>
</body>
</html>
