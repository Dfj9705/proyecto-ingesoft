<div class="pagetitle">
    <h1>Detalles del Proyecto</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item"><a href="/proyectos">Proyectos</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($proyecto['nombre']) ?></li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card border-info">
                <div class="card-header">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEpicas">
                        <i class="fas fa-layer-group me-2"></i> Gestionar Épicas
                    </button>

                </div>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($proyecto['nombre']) ?></h5>
                    <p class="text-muted mb-2"><i class="bi bi-calendar-week me-2"></i>
                        <strong>Inicio:</strong> <?= $proyecto['fecha_inicio'] ?> |
                        <strong>Fin:</strong> <?= $proyecto['fecha_fin'] ?>
                    </p>
                    <p><strong>Descripción:</strong><br><?= nl2br(htmlspecialchars($proyecto['descripcion'])) ?></p>
                    <p>
                        <?php if ($proyecto['creado_por'] == $_SESSION['user']->id): ?>
                            <span class="badge bg-success">Eres el creador de este proyecto</span>
                        <?php endif; ?>

                        <?php if (!empty($proyecto['rol_asignado'])): ?>
                            <span class="badge bg-secondary">Rol asignado: <?= ucwords($proyecto['rol_asignado']) ?></span>
                        <?php endif; ?>
                    </p>
                    <?php if (!empty($asignados)): ?>
                        <hr>
                        <h5 class="mt-4">Miembros del proyecto</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Rol asignado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($asignados as $i => $miembro): ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><?= htmlspecialchars($miembro['nombre']) ?></td>
                                            <td><?= htmlspecialchars($miembro['email']) ?></td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?= ucwords($miembro['rol_asignado']) ?>
                                                    <?php if ($_SESSION['user']->id == $miembro['usuario_id']): ?>
                                                        (Tú)
                                                    <?php endif; ?>
                                                </span>
                                            </td>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <a href="/proyectos" class="btn btn-outline-primary mt-3">Regresar a mis proyectos</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Épicas -->
<div class="modal fade" id="modalEpicas" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-primary">
            <div class="modal-header">
                <h5 class="modal-title">Gestión de Épicas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">

                <!-- Tabla de Épicas -->
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-bordered w-100" id="datatableEpicas">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Título</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <!-- Formulario -->
                <form id="formEpica" novalidate>
                    <input type="hidden" name="id" id="epica_id">
                    <input type="hidden" name="proyecto_id" value="<?= $proyecto['id'] ?>">

                    <div class="mb-3">
                        <label for="titulo">Título</label>
                        <input type="text" class="form-control" name="titulo" id="epica_titulo" required>
                        <div class="invalid-feedback">Debe ingresar un título.</div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="epica_descripcion" rows="3"></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" form="formEpica" class="btn btn-primary" id="btnGuardarEpica">
                    <span id="loaderEpica" class="spinner-border spinner-border-sm me-2" style="display:none;"></span>
                    Guardar
                </button>
            </div>
        </div>
    </div>
</div>
<script src="<?= asset('./build/js/proyectos/epicas.js') ?>"></script>