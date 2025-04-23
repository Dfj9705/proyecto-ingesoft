<div class="pagetitle">
    <h1>Gestión de Proyectos</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item">Proyectos</li>
            <li class="breadcrumb-item active">Listado</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title">Proyectos registrados</h5>
                        </div>
                        <div class="col d-flex justify-content-end align-items-center">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProyecto">
                                <i class="fas fa-plus me-2"></i>Nuevo
                            </button>
                        </div>
                    </div>
                    <p>Listado de proyectos activos</p>
                    <div class="row">
                        <div class="col table-responsive" style="min-height: 50vh;">
                            <table class="table table-striped table-bordered table-sm" id="datatableProyectos"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Proyecto -->
<div class="modal fade" id="modalProyecto" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleProyecto">Nuevo proyecto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formProyecto" novalidate>
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3">
                        <label for="nombre">Nombre del proyecto</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" required>
                        <div class="invalid-feedback">Debe ingresar un nombre.</div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" rows="3" required></textarea>
                        <div class="invalid-feedback">Debe ingresar una descripción.</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label for="fecha_inicio">Fecha de inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="fecha_fin">Fecha de fin</label>
                            <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" form="formProyecto" id="btnGuardar" class="btn btn-primary">
                    <span id="spanLoader" class="spinner-grow spinner-grow-sm me-2" aria-hidden="true"></span>
                    Guardar proyecto
                </button>
                <button type="button" id="btnModificar" class="btn btn-warning">
                    <span id="spanLoaderModificar" class="spinner-grow spinner-grow-sm me-2" aria-hidden="true"></span>
                    Modificar proyecto
                </button>
            </div>
        </div>
    </div>
</div><!-- End Modal -->

<script src="<?= asset('./build/js/proyectos/index.js') ?>"></script>