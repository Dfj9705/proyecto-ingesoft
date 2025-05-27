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
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Progreso del proyecto</h5>
            <p>
                <span class="badge bg-secondary me-1">Pendientes: <?= $estados['pendiente'] ?></span>
                <span class="badge bg-primary me-1">En progreso: <?= $estados['en_progreso'] ?></span>
                <span class="badge bg-success">Completadas: <?= $estados['completado'] ?></span>
            </p>

            <div class="progress" style="height: 20px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: <?= $porcentaje ?>%;"
                    aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100">
                    <?= $porcentaje ?>%
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card border-info">
                <div class="card-header">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEpicas">
                        <i class="fas fa-layer-group me-2"></i> Gestionar Épicas
                    </button>
                    <button class="btn btn-outline-info" data-bs-toggle="modal" data-proyecto="<?= $proyecto['id'] ?>"
                        data-bs-target="#modalTareas">
                        <i class="fas fa-tasks me-2"></i> Gestionar Tareas
                    </button>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalSprints">
                        <i class="fas fa-flag me-1"></i> Gestionar Sprints
                    </button>
                    <a href="/proyectos/kanban?id=<?= $proyecto['id'] ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-columns me-1"></i> Ir al tablero Kanban
                    </a>
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
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card border-info">
                <div id="contenedorAcordeonSprints" class="mt-4">
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

<!-- Modal Tareas -->
<div class="modal fade" id="modalTareas" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-info">
            <div class="modal-header">
                <h5 class="modal-title">Tareas del Proyecto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">

                <!-- Tabla de Tareas -->
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-striped table-bordered w-100" id="datatableTareas">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Título</th>
                                <th>Estado</th>
                                <th>Prioridad</th>
                                <th>Asignado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <!-- Formulario de Tareas -->
                <form id="formTarea" novalidate>
                    <input type="hidden" name="id" id="tarea_id">
                    <input type="hidden" name="proyecto_id" value="<?= $proyecto['id'] ?>">

                    <div class="mb-3">
                        <label for="tarea_titulo">Título</label>
                        <input type="text" class="form-control" name="titulo" id="tarea_titulo" required>
                        <div class="invalid-feedback">Debe ingresar un título.</div>
                    </div>

                    <div class="mb-3">
                        <label for="tarea_descripcion">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="tarea_descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="sprint_id">Sprint</label>
                        <select name="sprint_id" id="sprint_id" class="form-select">
                            <option value="">-- Sin sprint --</option>
                            <!-- Opciones se llenan por JS -->
                        </select>
                    </div>


                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label for="tarea_prioridad">Prioridad</label>
                            <select class="form-select" name="prioridad" id="tarea_prioridad">
                                <option value="alta">Alta</option>
                                <option value="media" selected>Media</option>
                                <option value="baja">Baja</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="tarea_estado">Estado</label>
                            <select class="form-select" name="estado" id="tarea_estado">
                                <option value="pendiente">Pendiente</option>
                                <option value="en_progreso">En progreso</option>
                                <option value="completado">Completado</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="tarea_asignado_a">Asignado a</label>
                            <select class="form-select" name="asignado_a" id="tarea_asignado_a" required>
                                <!-- Se llena con JS -->
                            </select>
                            <div class="invalid-feedback">Seleccione un responsable.</div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" form="formTarea" class="btn btn-primary" id="btnGuardarTarea">
                    <span id="loaderTarea" class="spinner-border spinner-border-sm me-2" style="display:none;"></span>
                    Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sprints -->
<div class="modal fade" id="modalSprints" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-primary">
            <div class="modal-header">
                <h5 class="modal-title">Gestión de Sprints</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">

                <!-- Tabla -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-sm table-hover w-100" id="datatableSprints"></table>
                </div>

                <!-- Formulario -->
                <form id="formSprint" novalidate>
                    <input type="hidden" name="id" id="sprint_id">
                    <input type="hidden" name="proyecto_id" value="<?= $proyecto['id'] ?>">

                    <div class="mb-3">
                        <label for="sprint_nombre">Nombre del Sprint</label>
                        <input type="text" class="form-control" name="nombre" id="sprint_nombre" required>
                        <div class="invalid-feedback">Ingrese un nombre.</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="sprint_fecha_inicio">Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" id="sprint_fecha_inicio"
                                required>
                        </div>
                        <div class="col">
                            <label for="sprint_fecha_fin">Fin</label>
                            <input type="date" class="form-control" name="fecha_fin" id="sprint_fecha_fin" required>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button form="formSprint" class="btn btn-primary" id="btnGuardarSprint">
                    <span id="spinnerSprint" class="spinner-border spinner-border-sm me-2"
                        style="display: none;"></span>
                    Guardar Sprint
                </button>
            </div>
        </div>
    </div>
</div>


<script src="<?= asset('./build/js/proyectos/epicas.js') ?>"></script>
<script src="<?= asset('./build/js/proyectos/tareas.js') ?>"></script>
<script src="<?= asset('./build/js/proyectos/sprints.js') ?>"></script>