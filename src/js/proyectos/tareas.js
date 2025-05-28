
import { Modal } from "bootstrap";
import DataTable from "datatables.net-bs5";
import { Toast, confirmacion } from "../funciones";

const modalTareasElement = document.getElementById('modalTareas');
const modalTareas = new Modal(modalTareasElement);
const formTarea = document.getElementById('formTarea');
const btnGuardarTarea = document.getElementById('btnGuardarTarea');
const loaderTarea = document.getElementById('loaderTarea');
const textTituloTarea = document.getElementById('tarea_titulo');
const selectResponsable = document.getElementById('tarea_asignado_a');

const datatableTareas = new DataTable('#datatableTareas', {
  data: [],
  columns: [
    { title: "#", render: (data, type, row, meta) => meta.row + 1 },
    { title: "Título", data: "titulo" },
    { title: "Estado", data: "estado" },
    { title: "Prioridad", data: "prioridad" },
    { title: "Asignado", data: "asignado_nombre" },
    { title: "Epica", data: "epica_id", render: (data, type, row) => row.epica_titulo || 'Sin épica' },
    { title: "Sprint", data: "sprint_id", render: (data, type, row) => row.sprint_nombre || 'Sin sprint' },
    {
      title: "Acciones",
      data: "id",
      render: (id, type, row) => `
        <button class="btn btn-sm btn-warning me-1 editar" data-id="${id}">Editar</button>
        <button class="btn btn-sm btn-danger eliminar" data-id="${id}">Eliminar</button>
      `,
      orderable: false
    }
  ]
});

const cargarTareas = async () => {
  const proyecto_id = formTarea.proyecto_id.value;
  const url = `/api/tareas/listar?proyecto_id=${proyecto_id}`;
  const response = await fetch(url);
  const data = await response.json();
  console.log(data)
  if (data.codigo === 1) {
    datatableTareas.clear().rows.add(data.datos).draw();
  } else {
    Toast.fire({ icon: 'info', title: data.mensaje });
  }
};

const cargarUsuariosProyecto = async () => {
  const proyecto_id = formTarea.proyecto_id.value;
  const url = `/api/proyecto-persona/listar?proyecto_id=${proyecto_id}`;
  const response = await fetch(url);
  const data = await response.json();

  if (data.codigo === 1) {
    selectResponsable.innerHTML = '<option value="">Seleccione</option>';
    data.datos.forEach(usuario => {
      const opt = document.createElement('option');
      opt.value = usuario.usuario_id;
      opt.textContent = usuario.nombre;
      selectResponsable.appendChild(opt);
    });
  }
};

const guardarTarea = async (e) => {
  e.preventDefault();
  loaderTarea.style.display = '';
  btnGuardarTarea.disabled = true;

  formTarea.classList.add('was-validated');
  if (!formTarea.checkValidity()) {
    loaderTarea.style.display = 'none';
    btnGuardarTarea.disabled = false;
    return;
  }

  const formData = new FormData(formTarea);
  const id = formData.get('id');
  const url = id ? "/api/tareas/modificar" : "/api/tareas/guardar";

  const config = {
    method: 'POST',
    body: formData
  };

  const response = await fetch(url, config);
  const data = await response.json();

  Toast.fire({ icon: data.codigo === 1 ? 'success' : 'info', title: data.mensaje });
  if (data.codigo === 1) {
    formTarea.reset();
    formTarea.classList.remove('was-validated');
    // modalTareas.hide();
    actualizarBarraProgreso(formTarea.proyecto_id.value);
    formTarea.tarea_id.value = '';
    cargarTareas();
  }

  loaderTarea.style.display = 'none';
  btnGuardarTarea.disabled = false;
};

const editarTarea = (e) => {
  const tr = e.currentTarget.closest('tr');
  const row = datatableTareas.row(tr).data();

  formTarea.id.value = row.id;
  formTarea.titulo.value = row.titulo;
  formTarea.descripcion.value = row.descripcion;
  formTarea.sprint_id.value = row.sprint_id;
  formTarea.epica_id_tarea.value = row.epica_id;
  formTarea.estado.value = row.estado;
  formTarea.prioridad.value = row.prioridad;
  formTarea.asignado_a.value = row.asignado_a;
  modalTareas.show();
};

const eliminarTarea = async (e) => {
  const confirm = await confirmacion('¿Eliminar esta tarea?', 'question', 'Sí, eliminar');
  if (!confirm) return;

  const id = e.currentTarget.dataset.id;
  const formData = new FormData();
  formData.append('id', id);

  const response = await fetch('/api/tareas/eliminar', {
    method: 'POST',
    body: formData
  });
  const data = await response.json();

  Toast.fire({ icon: data.codigo === 1 ? 'success' : 'info', title: data.mensaje });
  if (data.codigo === 1) cargarTareas();
};

const cargarSprintsEnSelect = async (proyecto_id) => {
  const select = document.getElementById('sprint_id');
  if (!select) return;

  const url = `/api/sprints/listar?proyecto_id=${proyecto_id}`;
  const response = await fetch(url);
  const data = await response.json();

  if (data.codigo === 1) {
    select.innerHTML = '<option value="">-- Sin sprint --</option>';
    data.datos.forEach(sprint => {
      const option = document.createElement('option');
      option.value = sprint.id;
      option.textContent = `${sprint.nombre} (${sprint.fecha_inicio} - ${sprint.fecha_fin})`;
      select.appendChild(option);
    });
  }
};
const cargarEpicasEnSelect = async (proyecto_id) => {
  const select = document.getElementById('epica_id_tarea');
  if (!select) return;

  const url = `/api/epicas/listar?proyecto_id=${proyecto_id}`;
  const response = await fetch(url);
  const data = await response.json();

  if (data.codigo === 1) {
    select.innerHTML = '<option value="">-- Sin épica --</option>';

    data.datos.forEach(epica => {
      const option = document.createElement('option');
      option.value = epica.id;
      option.textContent = `${epica.titulo}`;
      select.appendChild(option);
    });
  }
};

const actualizarBarraProgreso = async (proyecto_id) => {
  const contenedor = document.getElementById('progresoProyecto');
  if (!contenedor) return;

  try {
    const response = await fetch(`/api/tareas/progreso?proyecto_id=${proyecto_id}`);
    const data = await response.json();

    if (data.codigo === 1) {
      contenedor.innerHTML = `
        <p>
          <span class="badge bg-secondary me-1">Pendientes: ${data.estados.pendiente}</span>
          <span class="badge bg-primary me-1">En progreso: ${data.estados.en_progreso}</span>
          <span class="badge bg-success">Completadas: ${data.estados.completado}</span>
        </p>
        <div class="progress" style="height: 20px;">
          <div class="progress-bar bg-success" role="progressbar"
               style="width: ${data.porcentaje}%;" aria-valuenow="${data.porcentaje}"
               aria-valuemin="0" aria-valuemax="100">
            ${data.porcentaje}%
          </div>
        </div>
      `;
    }
  } catch (error) {
    console.error("Error actualizando barra de progreso:", error);
  }
};

const sugerirPrioridad = async (e) => {
  const titulo = e.target.value.trim();
  if (!titulo) {
    formTarea.prioridad.value = '';
    return;
  }
  const body = new FormData();
  body.append('titulo', titulo);
  const url = `/api/tareas/sugerir-prioridad`;
  try {
    const response = await fetch(url, {
      method: 'POST',
      body: body
    });
    const data = await response.json();
    console.log(data);
    if (data.codigo === 1) {
      formTarea.tarea_prioridad.value = data.prioridad;
    } else {
      formTarea.tarea_prioridad.value = '';
    }
  } catch (error) {
    console.error("Error sugiriendo prioridad:", error);
    formTarea.tarea_prioridad.value = '';
  }
}


formTarea.addEventListener('submit', guardarTarea);
modalTareasElement.addEventListener('show.bs.modal', (e) => {
  const proyecto_id = e.relatedTarget.dataset.proyecto
  cargarTareas();
  cargarUsuariosProyecto();
  cargarSprintsEnSelect(proyecto_id)
  cargarEpicasEnSelect(proyecto_id)
  formTarea.tarea_id.value = '';
  formTarea.reset();
});
datatableTareas.on('click', '.editar', editarTarea);
datatableTareas.on('click', '.eliminar', eliminarTarea);
textTituloTarea.addEventListener('change', sugerirPrioridad)
