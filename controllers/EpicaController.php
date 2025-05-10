<?php

namespace Controllers;

use Model\Epica;
use MVC\Router;
use Exception;

/**
 * Controlador para gestionar las épicas de cada proyecto.
 */
class EpicaController
{
    /**
     * Lista las épicas asociadas a un proyecto
     */
    public static function listarAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();

        $proyecto_id = $_GET['proyecto_id'] ?? null;

        if (!$proyecto_id) {
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Falta el ID del proyecto'
            ]);
            return;
        }

        $epicas = Epica::where('proyecto_id', $proyecto_id);

        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'Épicas obtenidas',
            'datos' => $epicas
        ]);
    }

    /**
     * Crea una nueva épica
     */
    public static function guardarAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();

        $data = sanitizar($_POST);
        $data['creado_en'] = date('Y-m-d H:i:s');
        $data['creado_por'] = $_SESSION['user']->id ?? null;

        if (empty($data['titulo']) || empty($data['proyecto_id'])) {
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Título y proyecto son obligatorios'
            ]);
            return;
        }

        try {
            $epica = new Epica($data);
            $epica->crear();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Épica creada correctamente'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al crear épica',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    /**
     * Modifica una épica existente
     */
    public static function modificarAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();

        $data = sanitizar($_POST);

        if (empty($data['id']) || empty($data['titulo'])) {
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'ID y título son requeridos'
            ]);
            return;
        }

        try {
            $registro = Epica::find($data['id']);
            if (!$registro) {
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'Épica no encontrada'
                ]);
                return;
            }

            $registro->sincronizar($data);
            $registro->actualizar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Épica actualizada correctamente'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al actualizar la épica',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    /**
     * Elimina una épica por su ID
     */
    public static function eliminarAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();

        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'ID de la épica es requerido'
            ]);
            return;
        }

        try {
            $registro = Epica::find($id);
            if (!$registro) {
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'Épica no encontrada'
                ]);
                return;
            }

            $registro->eliminar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Épica eliminada correctamente'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar épica',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}
