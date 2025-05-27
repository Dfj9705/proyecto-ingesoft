
import { Modal } from "bootstrap";
import DataTable from "datatables.net-bs5";
import { Toast, confirmacion } from "../funciones";


const modalSprints = new Modal(document.getElementById('modalSprints'));
const formSprint = document.getElementById('formSprint');
const btnGuardarSprint = document.getElementById('btnGuardarSprint');
const spinnerSprint = document.getElementById('spinnerSprint');

const datatableSprints = new DataTable('#datatableSprints', {
  data: [],
  columns: [
    { title: "#", render: (data, type, row, meta) => meta.row + 1 },
    { title: "Nombre", data: "nombre" },
    { title: "Inicio", data: "fecha_inicio" },
    { title: "Fin", data: "fecha_fin" },
    {
      title: "Acciones",
      data: "id",
      render: (id, type, row) => `
        <button class="btn btn-sm btn-warning editar" data-id="${id}">Editar</button>
        <button class="btn btn-sm btn-danger eliminar" data-id="${id}">Eliminar</button>
      `,
      orderable: false
    }
  ]
});

const cargarSprints = async () => {
  const proyecto_id = formSprint.proyecto_id.value;
  const url = `/api/sprints/listar?proyecto_id=${proyecto_id}`;
  const response = await fetch(url);
  const data = await response.json();

  if (data.codigo === 1) {
    datatableSprints.clear().rows.add(data.datos).draw();
  } else {
    Toast.fire({ icon: 'info', title: data.mensaje });
  }
};

const guardarSprint = async (e) => {
  e.preventDefault();
  spinnerSprint.style.display = '';
  btnGuardarSprint.disabled = true;

  formSprint.classList.add('was-validated');
  if (!formSprint.checkValidity()) {
    spinnerSprint.style.display = 'none';
    btnGuardarSprint.disabled = false;
    return;
  }

  const fecha_inicio = new Date(formSprint.fecha_inicio.value);
  const fecha_fin = new Date(formSprint.fecha_fin.value);
  if (fecha_fin < fecha_inicio) {
    Toast.fire({ icon: 'warning', title: 'La fecha de fin no puede ser menor a la de inicio.' });
    spinnerSprint.style.display = 'none';
    btnGuardarSprint.disabled = false;
    return;
  }

  const formData = new FormData(formSprint);
  const id = formData.get('id');
  const url = id ? '/api/sprints/modificar' : '/api/sprints/guardar';

  const response = await fetch(url, {
    method: 'POST',
    body: formData
  });

  const data = await response.json();
  Toast.fire({ icon: data.codigo === 1 ? 'success' : 'info', title: data.mensaje });

  if (data.codigo === 1) {
    formSprint.reset();
    formSprint.classList.remove('was-validated');
    // modalSprints.hide();
    actualizarAcordeonSprints(formSprint.proyecto_id.value);
    cargarSprints();
  }

  spinnerSprint.style.display = 'none';
  btnGuardarSprint.disabled = false;
};

const editarSprint = (e) => {
  const tr = e.currentTarget.closest('tr');
  const row = datatableSprints.row(tr).data();

  formSprint.id.value = row.id;
  formSprint.nombre.value = row.nombre;
  formSprint.fecha_inicio.value = row.fecha_inicio;
  formSprint.fecha_fin.value = row.fecha_fin;
  modalSprints.show();
};

const eliminarSprint = async (e) => {
  const confirm = await confirmacion('¿Eliminar este sprint?', 'question', 'Sí, eliminar');
  if (!confirm) return;

  const id = e.currentTarget.dataset.id;
  const formData = new FormData();
  formData.append('id', id);

  const response = await fetch('/api/sprints/eliminar', {
    method: 'POST',
    body: formData
  });
  const data = await response.json();

  Toast.fire({ icon: data.codigo === 1 ? 'success' : 'info', title: data.mensaje });
  if (data.codigo === 1) {
    cargarSprints()
    actualizarAcordeonSprints(formSprint.proyecto_id.value);
  };
};

const actualizarAcordeonSprints = async (proyecto_id) => {
  const contenedor = document.getElementById('contenedorAcordeonSprints');
  if (!contenedor) return;

  const url = `/api/sprints/listar?proyecto_id=${proyecto_id}`;
  const response = await fetch(url);
  const data = await response.json();

  if (data.codigo !== 1) {
    contenedor.innerHTML = '<p class="text-muted">No se pudieron cargar los sprints.</p>';
    return;
  }

  const sprints = data.datos;
  if (sprints.length === 0) {
    contenedor.innerHTML = '<p class="text-muted">No hay sprints registrados aún para este proyecto.</p>';
    return;
  }

  let html = '<div class="accordion" id="acordeonSprints">';

  sprints.forEach((sprint, index) => {
    html += `
      <div class="accordion-item">
        <h2 class="accordion-header" id="heading${sprint.id}">
          <button class="accordion-button" type="button"
                  data-bs-toggle="collapse" data-bs-target="#collapse${sprint.id}"
                  aria-expanded="false" aria-controls="collapse${sprint.id}">
            ${sprint.nombre}
            <span class="ms-auto text-muted small">
              ${sprint.fecha_inicio} → ${sprint.fecha_fin}
            </span>
          </button>
        </h2>
        <div id="collapse${sprint.id}" class="accordion-collapse collapse"
             aria-labelledby="heading${sprint.id}" data-bs-parent="#acordeonSprints">
          <div class="accordion-body">
            <p><strong>Inicio:</strong> ${sprint.fecha_inicio}<br>
               <strong>Fin:</strong> ${sprint.fecha_fin}</p>
            <p class="text-muted"><em>ID del Sprint: ${sprint.id}</em></p>
            <div class="tareasSprint" class="mt-3">
            </div>
          </div>
        </div>
      </div>`;
  });

  html += '</div>';
  contenedor.innerHTML = html;
};

actualizarAcordeonSprints(formSprint.proyecto_id.value).then(() => cargarTareasPorSprint());

const cargarTareasPorSprint = () => {
  const acordeon = document.getElementById('acordeonSprints');
  if (!acordeon) return;

  acordeon.addEventListener('show.bs.collapse', async (event) => {
    const panel = event.target;
    const sprintId = panel.id.replace('collapse', '');
    const body = panel.querySelector('.tareasSprint');

    // Mostrar loader
    body.innerHTML = '<div class="text-center text-muted">Cargando tareas...</div>';

    const url = `/api/tareas/listar/sprint?sprint_id=${sprintId}`;
    try {
      const response = await fetch(url);
      const data = await response.json();

      if (data.codigo === 1 && data.datos.length > 0) {
        const tareas = data.datos;
        let html = '<ul class="list-group">';

        tareas.forEach(tarea => {
          html += `<li class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                      <div class="fw-bold">${tarea.titulo}</div>
                      <small>${tarea.estado} • ${tarea.prioridad}</small>
                    </div>
                    <span class="badge rounded-pill bg-secondary">${tarea.asignado_nombre || 'Sin asignar'}</span>
                   </li>`;
        });

        html += '</ul>';
        body.innerHTML = html;
      } else {
        body.innerHTML = '<div class="text-muted">No hay tareas para este sprint.</div>';
      }
    } catch (error) {
      body.innerHTML = '<div class="text-danger">Error al cargar tareas.</div>';
      console.error(error);
    }
  });
};

formSprint.addEventListener('submit', guardarSprint);
document.getElementById('modalSprints').addEventListener('show.bs.modal', cargarSprints);
datatableSprints.on('click', '.editar', editarSprint);
datatableSprints.on('click', '.eliminar', eliminarSprint);
