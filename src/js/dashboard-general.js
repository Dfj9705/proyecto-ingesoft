import { Dropdown } from "bootstrap";
import { Toast } from "./funciones";

// Cargar tareas por estado
const cargarPorEstado = async () => {
    try {
        const res = await fetch(`/api/dashboard/tareas-por-estado`);
        const data = await res.json();
        if (data.codigo === 1) {
            const labels = Object.keys(data.estados);
            const valores = Object.values(data.estados);
            crearGrafico('chartEstado', 'bar', labels, valores, 'Cantidad de tareas');
        } else {
            Toast.fire({ icon: 'info', title: 'No se pudo cargar tareas por estado' });
        }
    } catch {
        Toast.fire({ icon: 'error', title: 'Error en la conexión' });
    }
};

// Cargar tareas por usuario
const cargarPorUsuario = async () => {
    try {
        const res = await fetch(`/api/dashboard/tareas-por-usuario`);
        const data = await res.json();
        if (data.codigo === 1) {
            const labels = data.datos.map(item => item.usuario || 'Sin asignar');
            const valores = data.datos.map(item => item.total);
            crearGrafico('chartUsuario', 'doughnut', labels, valores, 'Tareas asignadas');
        } else {
            Toast.fire({ icon: 'info', title: 'No se pudo cargar tareas por usuario' });
        }
    } catch {
        Toast.fire({ icon: 'error', title: 'Error en la conexión' });
    }
};

// Cargar tareas por proyecto
const cargarPorProyecto = async () => {
    try {
        const res = await fetch(`/api/dashboard/tareas-por-proyecto`);
        const data = await res.json();
        if (data.codigo === 1) {
            const labels = data.datos.map(item => item.proyecto || 'Sin proyecto');
            const valores = data.datos.map(item => item.total);
            crearGrafico('chartProyecto', 'bar', labels, valores, 'Tareas por proyecto');
        } else {
            Toast.fire({ icon: 'info', title: 'No se pudo cargar tareas por proyecto' });
        }
    } catch {
        Toast.fire({ icon: 'error', title: 'Error en la conexión' });
    }
};

// Función para crear gráficos
const crearGrafico = (ctxId, tipo, labels, data, label) => {
    const ctx = document.getElementById(ctxId).getContext('2d');
    new Chart(ctx, {
        type: tipo,
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: tipo === 'doughnut'
                    ? ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b']
                    : 'rgba(78, 115, 223, 0.5)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: tipo === 'doughnut' ? {} : {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
};

// Inicializar todas las gráficas cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    cargarPorEstado();
    cargarPorUsuario();
    cargarPorProyecto();
});
