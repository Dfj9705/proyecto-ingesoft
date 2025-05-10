
import { Modal } from "bootstrap";
import DataTable from "datatables.net-bs5";
import { Toast, confirmacion } from "../funciones";
import { lenguaje } from "../lenguaje";

const modalEpicasElement = document.getElementById('modalEpicas');
const modalEpicas = new Modal(modalEpicasElement);
const formEpica = document.getElementById('formEpica');
const btnGuardarEpica = document.getElementById('btnGuardarEpica');
const loaderEpica = document.getElementById('loaderEpica');
const datatableEpicas = new DataTable('#datatableEpicas', {
    data: [],
    language: lenguaje,
    columns: [
        { title: "#", render: (data, type, row, meta) => meta.row + 1 },
        { title: "Título", data: "titulo" },
        { title: "Descripción", data: "descripcion" },
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

const cargarEpicas = async () => {
    formEpica.reset();
    formEpica.classList.remove('was-validated');
    const proyecto_id = formEpica.proyecto_id.value;
    const url = `/api/epicas/listar?proyecto_id=${proyecto_id}`;
    const response = await fetch(url);
    const data = await response.json();

    if (data.codigo === 1) {
        datatableEpicas.clear().rows.add(data.datos).draw();
    } else {
        Toast.fire({ icon: 'info', title: data.mensaje });
    }
};

const guardarEpica = async (e) => {
    e.preventDefault();
    loaderEpica.style.display = '';
    btnGuardarEpica.disabled = true;

    formEpica.classList.add('was-validated');
    if (!formEpica.checkValidity()) {
        loaderEpica.style.display = 'none';
        btnGuardarEpica.disabled = false;
        return;
    }

    const formData = new FormData(formEpica);
    const id = formData.get('id');
    const url = id ? "/api/epicas/modificar" : "/api/epicas/guardar";

    const config = {
        method: 'POST',
        body: formData
    };

    const response = await fetch(url, config);
    const data = await response.json();

    Toast.fire({ icon: data.codigo === 1 ? 'success' : 'info', title: data.mensaje });
    if (data.codigo === 1) {
        formEpica.reset();
        formEpica.classList.remove('was-validated');
        formEpica.epica_id.value = '';
        cargarEpicas();
    }

    loaderEpica.style.display = 'none';
    btnGuardarEpica.disabled = false;
};

const editarEpica = (e) => {
    const id = e.currentTarget.dataset.id;
    const tr = e.currentTarget.closest('tr');
    const row = datatableEpicas.row(tr).data();

    formEpica.id.value = row.id;
    formEpica.titulo.value = row.titulo;
    formEpica.descripcion.value = row.descripcion;
    modalEpicas.show();
};

const eliminarEpica = async (e) => {
    const confirm = await confirmacion('¿Eliminar esta épica?', 'question', 'Sí, eliminar');
    if (!confirm) return;

    const id = e.currentTarget.dataset.id;
    const formData = new FormData();
    formData.append('id', id);

    const response = await fetch('/api/epicas/eliminar', {
        method: 'POST',
        body: formData
    });
    const data = await response.json();

    Toast.fire({ icon: data.codigo === 1 ? 'success' : 'info', title: data.mensaje });
    if (data.codigo === 1) cargarEpicas();
};

// Eventos
formEpica.addEventListener('submit', guardarEpica);
modalEpicasElement.addEventListener('show.bs.modal', cargarEpicas);
datatableEpicas.on('click', '.editar', editarEpica);
datatableEpicas.on('click', '.eliminar', eliminarEpica);
