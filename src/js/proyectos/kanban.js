
import Sortable from 'sortablejs';
import { Toast } from "../funciones";
import { Dropdown } from 'bootstrap';

const proyectoId = document.getElementById('kanbanContainer').dataset.proyecto;
const estados = ['pendiente', 'en_progreso', 'completado'];
const listas = {
  pendiente: document.getElementById('pendienteList'),
  en_progreso: document.getElementById('progresoList'),
  completado: document.getElementById('completadoList')
};

// Cargar tareas al iniciar
const cargarTareasKanban = async () => {
  const url = `/api/tareas/listar?proyecto_id=${proyectoId}`;
  const response = await fetch(url);
  const data = await response.json();

  if (data.codigo === 1) {
    estados.forEach(estado => listas[estado].innerHTML = '');
    data.datos.forEach(tarea => {
      const card = document.createElement('div');
      card.className = 'kanban-card';
      card.dataset.id = tarea.id;
      card.dataset.estado = tarea.estado;
      let color = '';
      switch (tarea.prioridad) {
        case 'alta':
          color = 'text-danger fw-bold';
          break;
        case 'media':
          color = 'text-warning fw-bold';
          break;
        case 'baja':
          color = 'text-success fw-bold';
          break;
      }
      card.innerHTML = `
        <strong>${tarea.titulo}</strong><br>
        <small><span class="${color}">${tarea.prioridad.toUpperCase()}</span> • ${tarea.asignado_nombre || 'Sin asignar'}</small>
      `;
      if (listas[tarea.estado]) listas[tarea.estado].appendChild(card);
    });
  } else {
    Toast.fire({ icon: 'info', title: data.mensaje });
  }
};

// Actualizar estado de la tarea cuando se mueve
const actualizarEstadoTarea = async (id, nuevoEstado) => {
  const formData = new FormData();
  formData.append('id', id);
  formData.append('estado', nuevoEstado);

  const response = await fetch('/api/tareas/modificar', {
    method: 'POST',
    body: formData
  });
  const data = await response.json();

  if (data.codigo !== 1) {
    Toast.fire({ icon: 'info', title: data.mensaje });
  }
};

// Inicializar drag and drop
estados.forEach(estado => {
  new Sortable(listas[estado], {
    group: 'kanban',
    animation: 150,
    onAdd: (event) => {
      const card = event.item;
      const id = card.dataset.id;
      const nuevoEstado = estado;
      actualizarEstadoTarea(id, nuevoEstado);
    }
  });
});

// Carga inicial
cargarTareasKanban();
