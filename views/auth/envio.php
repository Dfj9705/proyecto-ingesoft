<div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                    <div class="d-flex justify-content-center py-4">
                        <a href="/" class="logo d-flex align-items-center w-auto">
                            <img src="<?= asset('./images/scrum.png') ?>" alt="">
                            <span class="d-none d-lg-block">Scrum UMG</span>
                        </a>
                    </div><!-- End Logo -->

                    <div class="card mb-3 w-100">

                        <div class="card-body">
                            <input type="hidden" name="usu_id" value="<?= base64_encode($id) ?>">
                            <div class="pt-4 pb-2">
                                <h5 class="card-title text-center pb-0 fs-4">Cambio de contraseña</h5>
                                <p class="text-center small">Ingrese la dirección de correo registrada. Revise la
                                    bandeja de entrada de su correo y siga las instrucciones</p>
                            </div>

                            <form class="needs-validation" novalidate id="formEnvio">
                                <div class="row mb-3">
                                    <div class="col-12 mb-2">
                                        <label for="correo" class="form-label">Correo electrónico</label>
                                        <input type="text" name="correo" class="form-control" id="correo" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button id="btnEnvio" class="btn btn-primary w-100" type="submit"><span
                                                id="spanLoader" class="spinner-grow spinner-grow-sm me-2"
                                                aria-hidden="true"></span>Enviar instrucciones</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="credits">
                        <!-- All the links in the footer should remain intact. -->
                        <!-- You can delete the links only if you purchased the pro version. -->
                        <!-- Licensing information: https://bootstrapmade.com/license/ -->
                        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                        &copy; Copyright <strong><span>Desarrollo Web</span></strong>. All Rights Reserved
                    </div>

                </div>
            </div>
        </div>

    </section>

</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="<?= asset('./build/js/auth/envio.js') ?>"></script>