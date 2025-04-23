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
                            <div class="pt-4 pb-2">
                                <h5 class="card-title text-center pb-0 fs-4">Cambio de contraseña</h5>
                                <p class="text-center small">Ingrese y confirme su nueva contraseña</p>
                            </div>

                            <form class="needs-validation" novalidate id="formCambio">
                                <input type="hidden" name="id" value="<?= base64_encode($id) ?>">
                                <div class="row mb-3">
                                    <div class="col-12 mb-2">
                                        <label for="correo" class="form-label">Correo electrónico</label>
                                        <input type="text" name="correo" class="form-control" id="correo" disabled
                                            value="<?= $email ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 mb-2">
                                        <label for="password" class="form-label">Nueva Contraseña</label>
                                        <input type="password" name="password" class="form-control" id="password"
                                            required>
                                        <div class="invalid-feedback">Ingresa tu contraseña.</div>
                                    </div>

                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 mb-2">
                                        <label for="password2" class="form-label">Confirme contraseña</label>
                                        <input type="password" name="password2" class="form-control" id="password2"
                                            required>
                                        <div class="invalid-feedback" id="feedBackContraseña">
                                            Las contraseñas no coinciden.
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button id="btnCambio" class="btn btn-primary w-100" type="submit"><span
                                                id="spanLoader" class="spinner-grow spinner-grow-sm me-2"
                                                aria-hidden="true"></span>Cambiar contraseña</button>
                                    </div>
                                </div>
                            </form>

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
<script src="<?= asset('./build/js/auth/cambio.js') ?>"></script>