<div class="pagetitle">
    <h1>Detalles del Proyecto</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item"><a href="/proyectos">Proyectos</a></li>
            <li class="breadcrumb-item active"><a
                    href="/proyectos/ver?id=<?= $proyecto['id'] ?>"><?= htmlspecialchars($proyecto['nombre']) ?></a>
            </li>
            <li class="breadcrumb-item">Tareas</li>
        </ol>
    </nav>
</div><!-- End Page Title -->
<section class="section">
    <div class="row" id="kanbanContainer" data-proyecto="<?= $proyecto['id'] ?>">
        <div class="card border-info col-lg-12">
            <div class="card-body d-flex flex-row">


                <div class="col-lg-4">
                    <div class="kanban-column" data-estado="pendiente">
                        <h5 class="text-center text-secondary">Pendiente</h5>
                        <div class="kanban-list bg-light p-2 rounded" id="pendienteList"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="kanban-column" data-estado="en_progreso">
                        <h5 class="text-center text-primary">En Progreso</h5>
                        <div class="kanban-list bg-light p-2 rounded" id="progresoList"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="kanban-column" data-estado="completado">
                        <h5 class="text-center text-success">Completado</h5>
                        <div class="kanban-list bg-light p-2 rounded" id="completadoList"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= asset('./build/js/proyectos/kanban.js') ?>"></script>