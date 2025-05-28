<div class="container-fluid mt-4">
    <h2 class="mb-4">Dashboard General</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Tareas por estado</h5>
                            <canvas id="chartEstado"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Tareas por usuario</h5>
                            <canvas id="chartProyecto"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6" style="min-height: 100dvh;">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Tareas por proyecto</h5>
                    <canvas id="chartUsuario"></canvas>
                </div>
            </div>
        </div>
    </div>





</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= asset('./build/js/dashboard-general.js') ?>"></script>