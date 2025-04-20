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

                            <div class="pt-4 pb-2">
                                <h5 class="card-title text-center pb-0 fs-4">Regístrese</h5>
                                <p class="text-center small">Ingrese los datos solicitados</p>
                            </div>

                            <form class="needs-validation" novalidate id="formRegistro">
                                <div class="row mb-3">
                                    <div class="col-12 mb-2">
                                        <label for="nombre" class="form-label">Nombre completo</label>
                                        <input type="text" name="nombre" class="form-control" id="nombre" required>
                                        <div class="invalid-feedback">Ingrese su numero de DPI.</div>

                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 mb-2">
                                        <label for="correo" class="form-label">Correo electrónico</label>
                                        <input type="email" name="correo" class="form-control" id="correo" required>
                                        <div class="invalid-feedback">Ingrese su correo electrónico.</div>

                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 mb-2">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <input type="password" name="password" class="form-control" id="password"
                                            required>
                                        <div class="invalid-feedback">Ingrese su contraseña.</div>
                                    </div>

                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 mb-2">
                                        <label for="password2" class="form-label">Confirmar Contraseña</label>
                                        <input type="password" name="password2" class="form-control" id="password2"
                                            required>
                                        <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                                    </div>

                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <button id="btnRegistro" class="btn btn-primary w-100" type="submit"><span
                                                id="spanLoader" class="spinner-grow spinner-grow-sm me-2"
                                                aria-hidden="true"></span>Registrarse</button>
                                    </div>
                                </div>
                            </form>

                            <div class=" row justify-content-center mb-0">
                                <div class="col text-center">
                                    ¿Ya tiene una cuenta?
                                    <a href="/login">
                                        Inicie sesión
                                    </a>
                                </div>
                            </div>

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
<script src="<?= asset('./build/js/auth/registro.js') ?>"></script>