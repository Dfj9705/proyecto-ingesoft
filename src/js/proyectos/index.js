import { Modal } from "bootstrap";
import { confirmacion, Toast } from "../funciones";
import { lenguaje } from "../lenguaje";
import DataTable from "datatables.net-bs5";

const formProyecto = document.getElementById("formProyecto");
const modalProyectoElement = document.getElementById("modalProyecto");
const modalProyecto = new Modal(modalProyectoElement);
const btnGuardar = document.getElementById("btnGuardar");
const spanLoader = document.getElementById("spanLoader");
const btnModificar = document.getElementById("btnModificar");
const spanLoaderModificar = document.getElementById("spanLoaderModificar");
const modalTitleProyecto = document.getElementById("modalTitleProyecto");

spanLoader.style.display = "none";
btnGuardar.disabled = false;
spanLoaderModificar.style.display = "none";
btnModificar.disabled = true;
btnModificar.style.display = "none";

const datatableProyectos = new DataTable("#datatableProyectos", {
    data: null,
    language: lenguaje,
    columns: [
        { title: "No.", render: (data, type, row, meta) => meta.row + 1 },
        { title: "Nombre", data: "nombre" },
        { title: "Descripción", data: "descripcion" },
        { title: "Inicio", data: "fecha_inicio" },
        { title: "Fin", data: "fecha_fin" },
        {
            title: "Acciones",
            data: "id",
            orderable: false,
            searchable: false,
            render: (id) => {
                return `
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">Acciones</button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item modificar" data-id="${id}" style="cursor:pointer"><i class="fas fa-edit me-2"></i>Modificar</a></li>
                            <li><a class="dropdown-item eliminar text-danger" data-id="${id}" style="cursor:pointer"><i class="fas fa-trash me-2"></i>Eliminar</a></li>
                            <li><a class="dropdown-item ver-asignados text-primary" data-id="${id}" style="cursor:pointer"><i class="fas fa-users me-2"></i>Ver asignados</a></li>
                        </ul>
                    </div>
                `;
            }
        }
    ]
});

const buscarProyectos = async () => {
    try {
        const res = await fetch("/api/proyectos");
        const data = await res.json();
        if (data.codigo === 1) {
            datatableProyectos.clear().rows.add(data.datos).draw();
        } else {
            Toast.fire({ icon: "warning", title: data.mensaje });
        }
    } catch (error) {
        console.error(error);
        Toast.fire({ icon: "error", title: "Error al cargar proyectos", text: error.message });
    }
};

buscarProyectos();

const refrescarModal = () => {
    formProyecto.reset();
    formProyecto.classList.remove("was-validated");
    modalTitleProyecto.textContent = "Nuevo proyecto";
    btnModificar.style.display = "none";
    btnModificar.disabled = true;
    btnGuardar.style.display = "";
    btnGuardar.disabled = false;
};

const colocarDatos = (event) => {
    const row = event.target.closest("tr");
    const data = datatableProyectos.row(row).data();

    modalTitleProyecto.textContent = "Modificar proyecto";
    formProyecto.id.value = data.id;
    formProyecto.nombre.value = data.nombre;
    formProyecto.descripcion.value = data.descripcion;
    formProyecto.fecha_inicio.value = data.fecha_inicio;
    formProyecto.fecha_fin.value = data.fecha_fin;

    btnModificar.style.display = "";
    btnModificar.disabled = false;
    btnGuardar.style.display = "none";
    btnGuardar.disabled = true;

    modalProyecto.show();
};

const guardarProyecto = async (e) => {
    e.preventDefault();
    spanLoader.style.display = "";
    btnGuardar.disabled = true;

    formProyecto.classList.add("was-validated");
    if (!formProyecto.checkValidity()) {
        Toast.fire({ icon: "info", title: "Verifique la información ingresada" });
        spanLoader.style.display = "none";
        btnGuardar.disabled = false;
        return;
    }
    const inicio = new Date(formProyecto.fecha_inicio.value);
    const fin = new Date(formProyecto.fecha_fin.value);
    if (fin < inicio) {
        Toast.fire({ icon: "warning", title: "La fecha de fin no puede ser menor que la de inicio" });
        spanLoader.style.display = "none";
        btnGuardar.disabled = false;
        return;
    }
    try {
        const body = new FormData(formProyecto);
        const res = await fetch("/api/proyectos/crear", { method: "POST", body });
        const data = await res.json();
        if (data.codigo === 1) {
            Toast.fire({ icon: "success", title: data.mensaje });
            modalProyecto.hide();
            buscarProyectos();
        } else {
            Toast.fire({ icon: "warning", title: data.mensaje });
        }
    } catch (error) {
        console.error(error);
        Toast.fire({ icon: "error", title: "Error creando proyecto", text: error.message });
    }
    spanLoader.style.display = "none";
    btnGuardar.disabled = false;
};

const modificarProyecto = async (e) => {
    e.preventDefault();
    spanLoaderModificar.style.display = "";
    btnModificar.disabled = true;

    formProyecto.classList.add("was-validated");
    if (!formProyecto.checkValidity()) {
        Toast.fire({ icon: "info", title: "Verifique la información ingresada" });
        spanLoaderModificar.style.display = "none";
        btnModificar.disabled = false;
        return;
    }
    const inicio = new Date(formProyecto.fecha_inicio.value);
    const fin = new Date(formProyecto.fecha_fin.value);
    if (fin < inicio) {
        Toast.fire({ icon: "warning", title: "La fecha de fin no puede ser menor que la de inicio" });
        spanLoaderModificar.style.display = "none";
        btnModificar.disabled = false;
        return;
    }
    try {
        const body = new FormData(formProyecto);
        const res = await fetch("/api/proyectos/actualizar", { method: "POST", body });
        const data = await res.json();
        if (data.codigo === 1) {
            Toast.fire({ icon: "success", title: data.mensaje });
            modalProyecto.hide();
            buscarProyectos();
        } else {
            Toast.fire({ icon: "warning", title: data.mensaje });
        }
    } catch (error) {
        console.error(error);
        Toast.fire({ icon: "error", title: "Error actualizando proyecto", text: error.message });
    }
    spanLoaderModificar.style.display = "none";
    btnModificar.disabled = false;
};

const eliminarProyecto = async (e) => {
    const id = e.currentTarget.dataset.id;
    const confirmado = await confirmacion("¿Eliminar proyecto?", "question", "Sí, eliminar");
    if (!confirmado) return;

    try {
        const body = new FormData();
        body.append("id", id);
        const res = await fetch("/api/proyectos/eliminar", { method: "POST", body });
        const data = await res.json();
        if (data.codigo === 1) {
            Toast.fire({ icon: "success", title: data.mensaje });
            buscarProyectos();
        } else {
            Toast.fire({ icon: "warning", title: data.mensaje });
        }
    } catch (error) {
        console.error(error);
        Toast.fire({ icon: "error", title: "Error eliminando proyecto", text: error.message });
    }
};

// Modal y tabla de asignaciones
const modalAsignacionesElement = document.getElementById("modalAsignaciones");
const modalAsignaciones = new Modal(modalAsignacionesElement);
const formAsignacion = document.getElementById("formAsignacion");
const inputProyectoAsignar = document.getElementById("proyecto_id");
const selectUsuarioAsignar = document.getElementById("usuario_id");

const tablaAsignados = new DataTable("#tablaAsignados", {
    data: [],
    language: lenguaje,
    columns: [
        { title: "No.", render: (data, type, row, meta) => meta.row + 1 },
        { title: "Nombre", data: "usuario_nombre" },
        { title: "Email", data: "email" },
        {
            title: "Acciones",
            data: "usuario_id",
            render: (usuario_id, type, row) => {
                return `<button class='btn btn-danger btn-sm eliminar-asignado' data-user='${usuario_id}'>Eliminar</button>`;
            }
        }
    ]
});

const cargarAsignados = async (proyecto_id) => {
    inputProyectoAsignar.value = proyecto_id;
    await cargarUsuariosDisponibles(proyecto_id); // 👈 llamada aquí

    try {
        const res = await fetch(`/api/proyectos/asignados?proyecto_id=${proyecto_id}`);
        const data = await res.json();
        if (data.codigo === 1) {
            tablaAsignados.clear().rows.add(data.datos).draw();
            modalAsignaciones.show();
        } else {
            Toast.fire({ icon: 'warning', title: data.mensaje });
        }
    } catch (error) {
        Toast.fire({ icon: 'error', title: 'Error al cargar asignaciones', text: error.message });
    }
};


const asignarPersona = async (e) => {
    e.preventDefault();
    const body = new FormData(formAsignacion);

    try {
        const res = await fetch("/api/proyectos/asignar", {
            method: "POST",
            body
        });
        const data = await res.json();

        if (data.codigo === 1) {
            Toast.fire({ icon: 'success', title: data.mensaje });
            cargarAsignados(body.get("proyecto_id"));
            formAsignacion.reset();
        } else {
            Toast.fire({ icon: 'warning', title: data.mensaje });
        }
    } catch (error) {
        Toast.fire({ icon: 'error', title: 'Error al asignar persona', text: error.message });
    }
};

const eliminarAsignado = async (e) => {
    const button = e.target.closest('.eliminar-asignado');
    if (!button) return;

    const usuario_id = button.dataset.user;
    const proyecto_id = inputProyectoAsignar.value;
    const confirmar = await confirmacion('¿Eliminar asignación?', 'warning', 'Sí, eliminar');
    if (!confirmar) return;

    const body = new FormData();
    body.append("usuario_id", usuario_id);
    body.append("proyecto_id", proyecto_id);

    try {
        const res = await fetch("/api/proyectos/eliminar-asignacion", {
            method: "POST",
            body
        });
        const data = await res.json();
        if (data.codigo === 1) {
            Toast.fire({ icon: 'success', title: data.mensaje });
            cargarAsignados(proyecto_id);
        } else {
            Toast.fire({ icon: 'info', title: data.mensaje });
        }
    } catch (error) {
        Toast.fire({ icon: 'error', title: 'Error al eliminar asignación', text: error.message });
    }
};

// Evento para mostrar asignados desde el dropdown de la tabla de proyectos
datatableProyectos.on("click", ".ver-asignados", function (e) {
    const id = e.currentTarget.dataset.id;
    cargarAsignados(id);
});

const cargarUsuariosDisponibles = async (proyecto_id) => {
    try {
        const res = await fetch(`/api/proyectos/usuarios-disponibles?proyecto_id=${proyecto_id}`);
        const data = await res.json();
        if (data.codigo === 1) {
            selectUsuarioAsignar.innerHTML = '<option value="">-- Seleccione un usuario --</option>';
            data.datos.forEach(usuario => {
                const option = document.createElement('option');
                option.value = usuario.id;
                option.textContent = `${usuario.nombre} (${usuario.email})`;
                selectUsuarioAsignar.appendChild(option);
            });
        } else {
            Toast.fire({ icon: 'info', title: data.mensaje });
        }
    } catch (error) {
        Toast.fire({ icon: 'error', title: 'Error al cargar usuarios', text: error.message });
    }
};


formAsignacion?.addEventListener("submit", asignarPersona);
tablaAsignados.on("click", ".eliminar-asignado", eliminarAsignado);
formProyecto?.addEventListener("submit", guardarProyecto);
btnModificar?.addEventListener("click", modificarProyecto);
datatableProyectos.on("click", ".modificar", colocarDatos);
datatableProyectos.on("click", ".eliminar", eliminarProyecto);
modalProyectoElement.addEventListener("hidden.bs.modal", refrescarModal);
