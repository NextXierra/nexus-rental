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
    <link rel="stylesheet" href="/assets/css/rental.css?v=2">
</head>
<body>
<div class="page-shell">
    <nav id="mainNav" class="main-nav">
        <div class="container nav-inner">
            <a href="#" class="brand">Nexus Rental</a>
            <button id="mobileMenuButton" class="menu-button" type="button">Menu <i class="fa fa-bars"></i></button>
            <div class="desktop-menu">
                <ul>
                    <li><a href="#playstation">Fasilitas</a></li>
                    <li><a href="#availability">Cek Ketersediaan</a></li>
                    <li><a href="#playstationgames">Games</a></li>
                    <li><a href="#contact">Kontak</a></li>
                </ul>
            </div>
        </div>
        <div id="mobileMenu" class="mobile-menu" hidden>
            <ul>
                <li><a href="#playstation">Fasilitas</a></li>
                <li><a href="#availability">Cek Ketersediaan</a></li>
                <li><a href="#playstationgames">Games</a></li>
                <li><a href="#contact">Kontak</a></li>
            </ul>
        </div>
    </nav>

    <header class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <a href="#playstation" class="down-button"><i class="fa fa-angle-double-down"></i></a>
        </div>
    </header>

    <section id="playstation" class="section section-black">
        <div class="container">
            <div class="section-wrap">
                <h2>Nexus Rental Playstation <br>Lantai 1</h2>
                <div class="facility-grid">
                    <?php foreach ($playstationCards as $card): ?>
                        <div class="facility-card">
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
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section id="availability" class="section section-black">
        <div class="container">
            <div class="section-wrap">
                <h2>Cek Ketersediaan Playstation</h2>
                <div class="availability-grid">
                    <?php foreach ($availability as $ps): ?>
                        <div class="availability-card">
                            <?php if ($ps['status'] === 'available'): ?>
                                <span class="fa fa-check availability-icon available"></span>
                            <?php else: ?>
                                <span class="fa fa-ban availability-icon booked"></span>
                            <?php endif; ?>
                            <h6><?= esc($ps['time'] ?? '') ?></h6>
                            <div class="card-body"><h5><?= esc($ps['name']) ?></h5></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section id="playstationgames" class="section section-grey">
        <div class="container">
            <div class="games-heading"><h2>Playstation Games</h2></div>
            <div class="games-grid">
                <?php foreach ($psGames as $game): ?>
                    <div class="game-card">
                        <img src="<?= esc($game['img']) ?>" alt="<?= esc($game['name']) ?>">
                        <a role="button" href="#"><?= esc($game['name']) ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="contact" class="section section-black">
        <div class="container">
            <div class="contact-wrap">
                <h2>Kontak Kami</h2>
                <h4>Untuk booking dapat WA ke no. 0878-7720-7665</h4>
                <div class="contact-grid">
                    <div>
                        <form id="feedbackForm" class="feedback-form">
                            <div>
                                <label for="nama">Nama</label>
                                <input type="text" id="nama" placeholder="Masukkan Nama" required>
                            </div>
                            <div>
                                <label for="hp">No. Handphone / WA</label>
                                <input type="text" id="hp" placeholder="Masukkan Nomor HP/WA" required>
                            </div>
                            <div>
                                <label for="kritiksaran">Kritik dan Saran</label>
                                <textarea id="kritiksaran" rows="6" placeholder="Kritik dan Saran atau Request Game PS 4" required></textarea>
                            </div>
                            <button type="submit">Kirim</button>
                        </form>
                        <div id="successToast" class="success-toast" hidden>Terima kasih! Kritik dan saran Anda berhasil dikirim.</div>
                    </div>
                    <div class="contact-sidebar">
                        <div>
                            <h5>Alamat</h5>
                            <p>Jl. Danau Toba No. A7, Sawojajar, Kec. Kedungkandang, Kota Malang, Jawa Timur 65139</p>
                        </div>
                        <div>
                            <h5>Media Sosial</h5>
                            <div class="social-links">
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
<script src="/assets/js/rental.js?v=4"></script>
</body>
</html>
