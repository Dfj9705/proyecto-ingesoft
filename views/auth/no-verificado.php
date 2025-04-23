<div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                    <div class="d-flex justify-content-center py-4">
                        <img src="<?= asset('./images/scrum.png') ?>" alt=""
                            style="width: 45px; height: auto; object-fit: cover;">
                        <a href="/" class="logo d-flex align-items-center w-auto">
                            <span class="d-none d-lg-block">Scrum UMG</span>
                        </a>
                    </div><!-- End Logo -->

                    <div class="card mb-3 w-100">

                        <div class="card-body">
                            <?php if (isset($_SESSION['mensaje'])): ?>
                                <div class="alert alert-info alert-dismissible fade show mt-2" role="alert">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <span><?= $_SESSION['mensaje'] ?></span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            <?php endif ?>
                            <div class="pb-2">
                                <h5 class="card-title text-center pb-0 fs-4">No verificado</h5>
                                <p class="text-center small">Su cuenta no ha sido verificada</p>
                            </div>
                            <p style="text-align: justify;">Hemos enviado un mensaje de verificación al correo
                                <strong><?= enmascararCorreo($_SESSION['user']->email) ?></strong>, revise su bandeja
                                de entrada. Si no ha recibido ningún correo, presione <strong><a
                                        href="/reenviar">Aqui</a></strong> para reenviar
                                la confirmación. O intente puede intentar <strong><a href="/logout">Cerrando
                                        sesión</a></strong>.
                            </p>

                        </div>
                    </div>
                    <div class="credits">

                        &copy; Copyright <strong><span>Desarrollo Web</span></strong>. All Rights Reserved
                    </div>

                </div>
            </div>
        </div>

    </section>

</div>
<script src="<?= asset('./build/js/auth/login.js') ?>"></script>