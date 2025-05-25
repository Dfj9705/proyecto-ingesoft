
import { Modal } from "bootstrap";
import DataTable from "datatables.net-bs5";
import { Toast, confirmacion } from "../funciones";

const modalTareasElement = document.getElementById('modalTareas');
const modalTareas = new Modal(modalTareasElement);
const formTarea = document.getElementById('formTarea');
const btnGuardarTarea = document.getElementById('btnGuardarTarea');
const loaderTarea = document.getElementById('loaderTarea');
const selectResponsable = document.getElementById('tarea_asignado_a');

const datatableTareas = new DataTable('#datatableTareas', {
  data: [],
  columns: [
    { title: "#", render: (data, type, row, meta) => meta.row + 1 },
    { title: "Título", data: "titulo" },
    { title: "Estado", data: "estado" },
    { title: "Prioridad", data: "prioridad" },
    { title: "Asignado", data: "asignado_nombre" },
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


formTarea.addEventListener('submit', guardarTarea);
modalTareasElement.addEventListener('show.bs.modal', () => {
  cargarTareas();
  cargarUsuariosProyecto();
  formTarea.tarea_id.value = '';
  formTarea.reset();
});
datatableTareas.on('click', '.editar', editarTarea);
datatableTareas.on('click', '.eliminar', eliminarTarea);
