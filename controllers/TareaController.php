<?php

namespace Controllers;

use Model\Tarea;
use MVC\Router;
use Exception;
use PDO;

/**
 * Controlador para gestionar las tareas dentro de un proyecto.
 */
class TareaController
{
    /**
     * Lista las tareas por proyecto
     */
    public static function listarAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();
        header('Content-Type: application/json');

        $proyecto_id = $_GET['proyecto_id'] ?? null;

        if (!$proyecto_id) {
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Falta el ID del proyecto'
            ]);
            return;
        }

        try {
            $sql = "SELECT tareas.*, usuarios.nombre AS asignado_nombre, epicas.titulo AS epica_titulo, sprints.nombre AS sprint_nombre 
                    FROM tareas
                    LEFT JOIN usuarios ON usuarios.id = tareas.asignado_a
                    LEFT JOIN epicas on epicas.id = tareas.epica_id
                    LEFT JOIN sprints on sprints.id = tareas.sprint_id
            
                    WHERE tareas.proyecto_id = ?";

            $stmt = Tarea::getDB()->prepare($sql);
            $stmt->execute([$proyecto_id]);
            $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Tareas encontradas',
                'datos' => $tareas
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al consultar tareas',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    /**
     * Crea una nueva tarea
     */
    public static function guardarAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();
        header('Content-Type: application/json');

        $data = sanitizar($_POST);
        $data['creado_por'] = $_SESSION['user']->id ?? null;
        $data['creado_en'] = date('Y-m-d H:i:s');

        if (empty($data['titulo']) || empty($data['proyecto_id'])) {
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Título y proyecto son obligatorios'
            ]);
            return;
        }

        try {
            $tarea = new Tarea($data);
            $tarea->crear();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Tarea creada correctamente'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al crear tarea',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    /**
     * Modifica una tarea existente
     */
    public static function modificarAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();
        header('Content-Type: application/json');

        $data = sanitizar($_POST);

        if (empty($data['id'])) {
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'ID y título son requeridos'
            ]);
            return;
        }

        try {
            $tarea = Tarea::find($data['id']);
            if (!$tarea) {
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'Tarea no encontrada'
                ]);
                return;
            }

            $tarea->sincronizar($data);
            $tarea->actualizar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Tarea actualizada correctamente'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar tarea',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    /**
     * Elimina una tarea por su ID
     */
    public static function eliminarAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'ID de tarea requerido'
            ]);
            return;
        }

        try {
            $tarea = Tarea::find($id);
            if (!$tarea) {
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'Tarea no encontrada'
                ]);
                return;
            }

            $tarea->eliminar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Tarea eliminada correctamente'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar tarea',
                'detalle' => $e->getMessage()
            ]);
        }
    }
    /**
     * Lista las tareas por sprint
     */
    public static function listarSprintAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();
        header('Content-Type: application/json');

        $sprint_id = $_GET['sprint_id'] ?? null;

        if (!$sprint_id) {
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Falta el ID del sprint'
            ]);
            return;
        }

        try {
            $db = Tarea::getDB();
            $sql = "SELECT tareas.*, usuarios.nombre AS asignado_nombre
                FROM tareas
                LEFT JOIN usuarios ON tareas.asignado_a = usuarios.id
                WHERE tareas.sprint_id = ?
                ORDER BY tareas.creado_en DESC";

            $stmt = $db->prepare($sql);
            $stmt->execute([$sprint_id]);
            $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Tareas encontradas',
                'datos' => $tareas
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al consultar tareas',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}
