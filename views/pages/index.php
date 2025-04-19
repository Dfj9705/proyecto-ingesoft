<style>
  .carousel-item img {
    max-height: 300px;
    /* Cambia este valor según lo que necesites */
    object-fit: cover;
    width: 100%;
  }
</style>
<div class="row mb-3">
  <div class="col text-center">

    <h1>Proyecto 2 - Scrum UMG</h1>

  </div>
</div>
<div class="card">
  <div class="card-body">
    <h5 class="card-title text-center">Impulsamos tus sueños financieros, construyendo un mañana sólido y seguro.</h5>
    <p class="text-center"><small>Has iniciado sesión como: <strong><?= $_SESSION['user']->rol ?></strong></small></p>
    <div class="row mb-3 justify-content-center">
      <div class="col-lg-2 rounded-circle border border-info bg-info  bg-opacity-25">
        <img src="<?= asset('./images/scrum.png') ?>" alt="logo" class="w-100" srcset="">
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <ul class="list-group fs-5">
          <?php if ($_SESSION['user']->rol == 'USUARIO'): ?>
              <a href="/mis-cuentas" class="list-group-item list-group-item-action"><i
                  class="bi bi-cash-stack me-1 text-primary"></i>
                Ver mis cuentas</a>
              <a href="/terceros" class="list-group-item list-group-item-action"><i
                  class="bi bi-person-add me-1 text-success"></i>
                Agregar cuentas de terceros</a>
          <?php endif ?>
          <?php if ($_SESSION['user']->rol == 'CAJERO'): ?>
              <a href="/cuentas" class="list-group-item list-group-item-action"><i
                  class="bi bi-window-plus me-1 text-success"></i>
                Crear cuentas</a>
              <a href="/cajero" class="list-group-item list-group-item-action"><i
                  class="bi bi-arrow-left-right me-1 text-warning"></i>
                Realizar transacciones</a>
          <?php endif ?>
          <?php if ($_SESSION['user']->rol == 'ADMINISTRADOR'): ?>
              <a href="/admin/cajeros" class="list-group-item list-group-item-action"><i
                  class="bi bi-folder-plus me-1 text-success"></i>
                Agregar cajeros</a>
              <a href="/admin/monitor" class="list-group-item list-group-item-action"><i class="bi bi-graph-up-arrow
 me-1 text-danger"></i>
                Monitor</a>
          <?php endif ?>
        </ul><!-- End List group With Icons -->

      </div>
    </div>
  </div>
</div>

<script src="<?= asset('./build/js/inicio.js') ?>"></script>