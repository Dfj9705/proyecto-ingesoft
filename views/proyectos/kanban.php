<div class="row" id="kanbanContainer" data-proyecto="<?= $proyecto['id'] ?>">
    <div class="col-md-4">
        <div class="kanban-column" data-estado="pendiente">
            <h5 class="text-center text-secondary">Pendiente</h5>
            <div class="kanban-list bg-light p-2 rounded" id="pendienteList"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kanban-column" data-estado="en_progreso">
            <h5 class="text-center text-primary">En Progreso</h5>
            <div class="kanban-list bg-light p-2 rounded" id="progresoList"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kanban-column" data-estado="completado">
            <h5 class="text-center text-success">Completado</h5>
            <div class="kanban-list bg-light p-2 rounded" id="completadoList"></div>
        </div>
    </div>
</div>
<script src="<?= asset('./build/js/proyectos/kanban.js') ?>"></script>