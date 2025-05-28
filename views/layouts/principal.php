<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>Scrum UMG</title>
        <meta content="" name="description">
        <meta content="" name="keywords">

        <!-- Favicons -->
        <link href="<?= asset('./images/scrum.png') ?>" rel="icon">
        <link href="<?= asset('./images/scrum.png') ?>" rel="apple-touch-icon">

        <!-- Google Fonts -->
        <link href="https://fonts.gstatic.com" rel="preconnect">
        <link
            href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
            rel="stylesheet">

        <!-- Template Main CSS File -->
        <link href="<?= asset('./build/css/styles.css') ?>" rel="stylesheet">
        <script defer src="<?= asset('./build/js/app.js') ?>"></script>
    </head>

    <body>

        <!-- ======= Header ======= -->
        <header id="header" class="header fixed-top d-flex align-items-center">

            <div class="d-flex align-items-center justify-content-between">
                <img src="<?= asset('./images/scrum.png') ?>" alt="Logo"
                    style="width: 45px; height: auto; object-fit: cover;">
                <a href="/" class="logo d-flex align-items-center">
                    <span class="d-none d-lg-block">Scrum UMG</span>
                </a>
                <i class="bi bi-list toggle-sidebar-btn"></i>
            </div>
            <!-- End Logo -->


            <!-- End Search Bar -->
            <nav class="header-nav ms-auto">
                <ul class="d-flex align-items-center">

                    <li class="nav-item d-block d-lg-none">
                        <a class="nav-link nav-icon search-bar-toggle " href="#">
                            <i class="bi bi-search"></i>
                        </a>
                    </li><!-- End Search Icon-->

                    <!-- Perfil de usuario -->
                    <li class="nav-item dropdown pe-3">
                        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                            data-bs-toggle="dropdown">
                            <img src="<?= asset('images/usuario.png') ?>" alt="Profile" class="rounded-circle">
                            <span
                                class="d-none d-md-block dropdown-toggle ps-2"><?= $_SESSION['user']->nombre ?? "Nombre de usuario" ?></span>
                        </a><!-- End Profile Image Icon -->

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                            <li class="dropdown-header">
                                <h6><?= $_SESSION['user']->nombre ? $_SESSION['user']->nombre : "Nombre de Usuario" ?>
                                </h6>
                                <span><?= $_SESSION['user']->rol ?? "Rol" ?></span>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="/logout">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Cerrar sesión</span>
                                </a>
                            </li>
                        </ul><!-- End Profile Dropdown Items -->
                    </li><!-- End Profile Nav -->

                </ul>
            </nav><!-- End Icons Navigation -->


        </header><!-- End Header -->

        <!-- ======= Sidebar ======= -->
        <aside id="sidebar" class="sidebar">

            <ul class="sidebar-nav" id="sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#administracion" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-gear"></i><span>Administración</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="administracion" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="/proyectos">
                                <i class="bi bi-circle"></i><span>Proyectos</span>
                            </a>
                        </li>
                        <li>
                            <a href="/dashboard">
                                <i class="bi bi-circle"></i><span>Dashboard</span>
                            </a>
                        </li>

                    </ul>
                </li><!-- End Forms Nav -->

            </ul>


        </aside><!-- End Sidebar-->

        <main id="main" class="main">
            <?= $contenido ?>
        </main><!-- End #main -->

        <!-- ======= Footer ======= -->
        <footer id="footer" class="footer">
            <div class="copyright">
                &copy; Copyright <strong><span>Desarrollo Web</span></strong>. All Rights Reserved
            </div>
        </footer><!-- End Footer -->

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                class="bi bi-arrow-up-short"></i></a>

        <script src="https://kit.fontawesome.com/0ac58cc75c.js" crossorigin="anonymous"></script>
    </body>

</html>