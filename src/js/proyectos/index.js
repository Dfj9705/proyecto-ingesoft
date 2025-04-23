import { Modal } from "bootstrap";
import { confirmacion, Toast } from "../funciones";
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
            render: (data) => {
                return `<div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">Acciones</button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item modificar" data-id="${data}" style="cursor:pointer"><i class="fas fa-edit me-2"></i>Modificar</a></li>
                        <li><a class="dropdown-item eliminar text-danger" data-id="${data}" style="cursor:pointer"><i class="fas fa-trash me-2"></i>Eliminar</a></li>
                    </ul>
                </div>`;
            }
        }
    ]
});

const buscarProyectos = async () => {
    try {
        const res = await fetch("/api/proyectos");
        const data = await res.json();
        if (data.codigo === 1) {
            datatableProyectos.clear().rows.add(data.data).draw();
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

formProyecto?.addEventListener("submit", guardarProyecto);
btnModificar?.addEventListener("click", modificarProyecto);
datatableProyectos.on("click", ".modificar", colocarDatos);
datatableProyectos.on("click", ".eliminar", eliminarProyecto);
modalProyectoElement.addEventListener("hidden.bs.modal", refrescarModal);
