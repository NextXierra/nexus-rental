<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nexus Rental</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;700&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/rental.css?v=4">
</head>
<body>
<div class="page-shell">
    <nav id="mainNav" class="navbar navbar-expand-lg main-nav">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="#" class="navbar-brand brand">Nexus Rental</a>
            <button id="mobileMenuButton" class="navbar-toggler menu-button d-flex d-lg-none align-items-center" type="button">Menu <i class="fa fa-bars"></i></button>
            <div class="desktop-menu d-none d-lg-flex align-items-center">
                <ul class="navbar-nav d-flex align-items-center">
                    <li><a href="#playstation">Fasilitas</a></li>
                    <li><a href="#availability">Cek Ketersediaan</a></li>
                    <li><a href="#playstationgames">Games</a></li>
                    <li><a href="#contact">Kontak</a></li>
                    <li><a href="#" class="profile-placeholder rounded-circle d-flex align-items-center justify-content-center" aria-label="Login placeholder"><i class="fa fa-user"></i></a></li>
                </ul>
            </div>
        </div>
        <div id="mobileMenu" class="mobile-menu" hidden>
            <ul class="navbar-nav d-flex flex-column">
                <li><a href="#playstation">Fasilitas</a></li>
                <li><a href="#availability">Cek Ketersediaan</a></li>
                <li><a href="#playstationgames">Games</a></li>
                <li><a href="#contact">Kontak</a></li>
                <li><a href="#" class="profile-placeholder rounded-circle d-flex align-items-center justify-content-center" aria-label="Login placeholder"><i class="fa fa-user"></i></a></li>
            </ul>
        </div>
    </nav>

    <header class="hero d-flex align-items-center justify-content-center">
        <div class="hero-overlay"></div>
        <div class="hero-content d-flex align-items-end justify-content-center">
            <a href="#playstation" class="down-button d-flex align-items-center justify-content-center"><i class="fa fa-angle-double-down"></i></a>
        </div>
    </header>

    <section id="playstation" class="section section-black d-flex align-items-center">
        <div class="container">
            <div class="section-wrap">
                <h2>Nexus Rental Playstation</h2>
                <div class="row">
                    <?php foreach ($playstationCards as $card): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card facility-card">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center h-100">
                                    <?php if (isset($card['icon'])): ?>
                                        <span class="fa <?= esc($card['icon']) ?> facility-icon"></span>
                                    <?php else: ?>
                                        <img class="facility-image <?= str_contains($card['image'], 'Playstation') ? 'playstation-image' : '' ?>" src="<?= esc($card['image']) ?>" alt="">
                                    <?php endif; ?>
                                    <h4><?= esc($card['title']) ?></h4>
                                    <?php if (isset($card['subtitle'])): ?>
                                        <p><?= esc($card['subtitle']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section id="availability" class="section section-black d-flex align-items-center">
        <div class="container">
            <div class="section-wrap">
                <h2>Cek Ketersediaan Playstation</h2>
                <div class="row">
                    <?php foreach ($availability as $ps): ?>
                        <div class="col-sm-6 col-md-4 mb-4">
                            <div class="card availability-card">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center h-100">
                                    <?php if ($ps['status'] === 'available'): ?>
                                        <span class="fa fa-check availability-icon available"></span>
                                    <?php else: ?>
                                        <span class="fa fa-ban availability-icon booked"></span>
                                    <?php endif; ?>
                                    <h6><?= esc($ps['time'] ?? '') ?></h6>
                                    <h5><?= esc($ps['name']) ?></h5>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section id="playstationgames" class="section section-grey d-flex align-items-center">
        <div class="container">
            <div class="games-heading"><h2>Playstation Games</h2></div>
            <div class="row games-grid">
                <?php foreach ($psGames as $game): ?>
                    <div class="col-sm-6 col-md-4 mb-4">
                        <div class="card game-card d-flex flex-column align-items-center">
                            <img class="card-img-top" src="<?= esc($game['img']) ?>" alt="<?= esc($game['name']) ?>">
                            <a class="btn game-button" role="button" href="#"><?= esc($game['name']) ?></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="contact" class="section section-black d-flex align-items-center">
        <div class="container">
            <div class="contact-wrap">
                <h2>Kontak Kami</h2>
                <h4>Untuk booking dapat WA ke no. 08xx-xxxx-xxxx</h4>
                <div class="row contact-grid">
                    <div class="col-md-8 mb-4 mb-md-0">
                        <form id="feedbackForm" class="feedback-form">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" id="nama" placeholder="Masukkan Nama" required>
                            </div>
                            <div class="form-group">
                                <label for="hp">No. Handphone / WA</label>
                                <input type="text" class="form-control" id="hp" placeholder="Masukkan Nomor HP/WA" required>
                            </div>
                            <div class="form-group">
                                <label for="kritiksaran">Kritik dan Saran</label>
                                <textarea class="form-control" id="kritiksaran" rows="6" placeholder="Kritik dan Saran atau Request Game PS 4" required></textarea>
                            </div>
                            <button type="submit" class="btn submit-button">Kirim</button>
                        </form>
                        <div id="successToast" class="success-toast" hidden>Terima kasih! Kritik dan saran Anda berhasil dikirim.</div>
                    </div>
                    <div class="col-md-4 contact-sidebar d-flex flex-column">
                        <div>
                            <h5>Alamat</h5>
                            <p>Jl. Nama Jalan No. 00, Kecamatan, Kota, Provinsi 00000</p>
                        </div>
                        <div>
                            <h5>Media Sosial</h5>
                            <div class="social-links d-flex">
                                <a href="#"><i class="fa fa-facebook-official"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="mailto:nexusrentalps@gmail.com"><i class="fa fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer><div class="container"><p>Copyright &copy; Nexus Rental 2026</p></div></footer>
</div>
<script src="/vendor/jquery/jquery.slim.min.js"></script>
<script src="/vendor/popper/popper.min.js"></script>
<script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/js/rental.js?v=5"></script>
</body>
</html>
