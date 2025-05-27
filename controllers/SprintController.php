<?php

namespace Controllers;

use Exception;
use Model\Sprint;
use MVC\Router;

/**
 * Controlador para gestionar los sprints de un proyecto
 */
class SprintController
{
    /**
     * Lista los sprints de un proyecto
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
            $sprints = Sprint::where('proyecto_id', $proyecto_id, '=');
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Sprints encontrados',
                'datos' => $sprints
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al consultar sprints',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    /**
     * Guarda un nuevo sprint
     */
    public static function guardarAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();
        header('Content-Type: application/json');

        $data = sanitizar($_POST);

        if (empty($data['nombre']) || empty($data['proyecto_id'])) {
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Nombre y proyecto son obligatorios'
            ]);
            return;
        }

        try {
            $sprint = new Sprint($data);
            $sprint->crear();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Sprint creado correctamente'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar el sprint',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    /**
     * Modifica un sprint existente
     */
    public static function modificarAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();
        header('Content-Type: application/json');

        $data = sanitizar($_POST);

        if (empty($data['id']) || empty($data['nombre'])) {
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'ID y nombre del sprint son requeridos'
            ]);
            return;
        }

        try {
            $sprint = Sprint::find($data['id']);
            if (!$sprint) {
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'Sprint no encontrado'
                ]);
                return;
            }

            $sprint->sincronizar($data);
            $sprint->actualizar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Sprint actualizado correctamente'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar el sprint',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    /**
     * Elimina un sprint
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
                'mensaje' => 'ID del sprint requerido'
            ]);
            return;
        }

        try {
            $sprint = Sprint::find($id);
            if (!$sprint) {
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'Sprint no encontrado'
                ]);
                return;
            }

            $sprint->eliminar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Sprint eliminado correctamente'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el sprint',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}
