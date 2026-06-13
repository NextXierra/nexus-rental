<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard User - Nexus Rental</title>
    <link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css?v=3">
</head>
<body class="dashboard-body user-dashboard">
<div class="dashboard-shell">
    <aside class="dashboard-sidebar">
        <a href="/" class="dashboard-brand">Nexus Rental</a>
        <nav class="dashboard-nav">
            <a href="/dashboard/user" class="active"><i class="fa fa-home"></i> Home</a>
            <a href="/dashboard/user/reservasi"><i class="fa fa-calendar-check-o"></i> Reservasi Saya</a>
            <a href="#"><i class="fa fa-user"></i> Profil</a>
            <a href="/logout"><i class="fa fa-sign-out"></i> Logout</a>
        </nav>
    </aside>
    <main class="dashboard-main">
        <header class="dashboard-topnav">
            <form class="dashboard-search" action="#" method="get">
                <input type="text" name="search" placeholder="Search for...">
                <button type="submit" aria-label="Search"><i class="fa fa-search"></i></button>
            </form>
            <div class="dashboard-user-menu">
                <span>User</span>
                <a href="#" class="dashboard-profile" aria-label="Profil"><i class="fa fa-user"></i></a>
            </div>
        </header>
        <section class="dashboard-content-blank"></section>
    </main>
</div>
<script src="/vendor/jquery/jquery.slim.min.js"></script>
<script src="/vendor/popper/popper.min.js"></script>
<script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
