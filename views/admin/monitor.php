<div class="pagetitle">

    <h1>Monitor de transacciones</h1>

    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item">Admin</li>
            <li class="breadcrumb-item active">Monitor</li>
        </ol>
    </nav>

</div><!-- End Page Title -->
<section class="section dashboard">
    <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
            <div class="row">

                <!-- cuentas Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card cuentas-card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li><button data-tipo="1" id="filtroCuenta1" class="dropdown-item">Hoy</button></li>
                                <li><button data-tipo="2" id="filtroCuenta2" class="dropdown-item">Mes</button></li>
                                <li><button data-tipo="3" id="filtroCuenta3" class="dropdown-item">Año</button></li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Cuentas <span id="spanCuentas">| Hoy</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-piggy-bank"></i>
                                </div>
                                <div class="ps-3">
                                    <h6 id="cuentasCantidad">0</h6>

                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End cuentas Card -->

                <!-- cuentas Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card cuentas-card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li><button data-tipo="1" id="filtroUsuario1" class="dropdown-item">Hoy</button></li>
                                <li><button data-tipo="2" id="filtroUsuario2" class="dropdown-item">Mes</button></li>
                                <li><button data-tipo="3" id="filtroUsuario3" class="dropdown-item">Año</button></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Usuarios <span id="spanUsuarios">| Hoy</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6 id="usuariosCantidad">0</h6>

                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End cuentas Card -->

                <!-- transacciones Card -->
                <div class="col-xxl-4 col-xl-6">

                    <div class="card info-card cuentas-card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li><button data-tipo="1" id="filtroTransaccion1" class="dropdown-item">Hoy</button>
                                </li>
                                <li><button data-tipo="2" id="filtroTransaccion2" class="dropdown-item">Mes</button>
                                </li>
                                <li><button data-tipo="3" id="filtroTransaccion3" class="dropdown-item">Año</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Transacciones<span id="spanTransacciones">| Hoy</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-arrow-left-right"></i>
                                </div>
                                <div class="ps-3">
                                    <h6 id="transaccionesCantidad">0</h6>


                                </div>
                            </div>

                        </div>
                    </div>

                </div><!-- End transacciones Card -->

                <!-- depositos Card -->
                <div class="col-xxl-4 col-xl-6">

                    <div class="card info-card depositos-card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li><button data-movimiento="D" data-tipo="1" id="filtroDeposito1"
                                        class="dropdown-item">Hoy</button>
                                </li>
                                <li><button data-movimiento="D" data-tipo="2" id="filtroDeposito2"
                                        class="dropdown-item">Mes</button>
                                </li>
                                <li><button data-movimiento="D" data-tipo="3" id="filtroDeposito3"
                                        class="dropdown-item">Año</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Depositos<span id="spanDepositos">| Hoy</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                </div>
                                <div class="ps-3">
                                    <h6 id="depositosCantidad">0</h6>


                                </div>
                            </div>

                        </div>
                    </div>

                </div><!-- End depositos Card -->

                <!-- retiros Card -->
                <div class="col-xxl-4 col-xl-6">

                    <div class="card info-card retiros-card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li><button data-movimiento="R" data-tipo="1" id="filtroRetiro1"
                                        class="dropdown-item">Hoy</button>
                                </li>
                                <li><button data-movimiento="R" data-tipo="2" id="filtroRetiro2"
                                        class="dropdown-item">Mes</button>
                                </li>
                                <li><button data-movimiento="R" data-tipo="3" id="filtroRetiro3"
                                        class="dropdown-item">Año</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Retiros<span id="spanRetiros">| Hoy</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-box-arrow-right"></i>
                                </div>
                                <div class="ps-3">
                                    <h6 id="retirosCantidad">0</h6>


                                </div>
                            </div>

                        </div>
                    </div>

                </div><!-- End retiros Card -->
                <!-- transferencias Card -->
                <div class="col-xxl-4 col-xl-6">

                    <div class="card info-card cuentas-card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li><button data-movimiento="T" data-tipo="1" id="filtroTransferencia1"
                                        class="dropdown-item">Hoy</button>
                                </li>
                                <li><button data-movimiento="T" data-tipo="2" id="filtroTransferencia2"
                                        class="dropdown-item">Mes</button>
                                </li>
                                <li><button data-movimiento="T" data-tipo="3" id="filtroTransferencia3"
                                        class="dropdown-item">Año</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Transferencias<span id="spanTransferencias">| Hoy</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-shuffle"></i>
                                </div>
                                <div class="ps-3">
                                    <h6 id="transferenciasCantidad">0</h6>


                                </div>
                            </div>

                        </div>
                    </div>

                </div><!-- End transferencias Card -->

                <!-- Reports -->
                <div class="col-12">
                    <div class="card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li><button data-movimiento="T" data-tipo="1" id="filtroSuma1"
                                        class="dropdown-item">Hoy</button>
                                </li>
                                <li><button data-movimiento="T" data-tipo="2" id="filtroSuma2"
                                        class="dropdown-item">Mes</button>
                                </li>
                                <li><button data-movimiento="T" data-tipo="3" id="filtroSuma3"
                                        class="dropdown-item">Año</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Cantidades<span id="spanCantidades">| Hoy</span></h5>

                            <!-- Line Chart -->
                            <canvas id="cantidadesChart"></canvas>
                            <!-- End Line Chart -->

                        </div>

                    </div>
                </div><!-- End Reports -->

            </div>
        </div><!-- End Left side columns -->

    </div>
</section>

<script src="<?= asset('./build/js/admin/monitor.js') ?>"></script>