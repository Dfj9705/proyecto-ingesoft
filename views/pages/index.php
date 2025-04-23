<div class="row mb-3">
  <div class="col text-center">
    <h1>Proyecto 2 - Scrum UMG</h1>
  </div>
</div>

<div class="card mb-4">
  <div class="card-body">
    <h5 class="card-title text-center">Impulsamos tus sueños financieros, construyendo un mañana sólido y seguro.</h5>
    <p class="text-center">
      <small>Has iniciado sesión como: <strong><?= $_SESSION['user']->rol ?></strong></small>
    </p>
    <div class="row">
      <?php foreach ($proyectos as $proyecto): ?>
        <div class="col-md-4 mb-4">

          <div class="card shadow-sm border border-primary position-relative">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($proyecto['nombre']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($proyecto['descripcion']) ?></p>

              <p>
                <?php if ($proyecto['creado_por'] == $_SESSION['user']->id): ?>
                  <span class="badge bg-success">Creador</span>
                <?php endif; ?>

                <?php if (!empty($proyecto['rol_asignado'])): ?>
                  <span class="badge bg-secondary">Rol: <?= htmlspecialchars(ucwords($proyecto['rol_asignado'])) ?></span>
                <?php endif; ?>
              </p>

              <p><strong>Inicio:</strong> <?= $proyecto['fecha_inicio'] ?> <br>
                <strong>Fin:</strong> <?= $proyecto['fecha_fin'] ?>
              </p>

              <a href="/proyectos/ver?id=<?= $proyecto['id'] ?>" class="stretched-link"></a>
            </div>
          </div>

        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>


<script src="<?= asset('./build/js/inicio.js') ?>"></script>