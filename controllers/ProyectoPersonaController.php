<?php

namespace Controllers;

use Exception;
use Model\ProyectoPersona;
use MVC\Router;

/**
 * Controlador para gestionar la asignación de personas a proyectos.
 */
class ProyectoPersonaController
{
    /**
     * Asigna un usuario a un proyecto (API).
     * Requiere proyecto_id y usuario_id en el body.
     */
    public static function asignarAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();
        header('Content-Type: application/json');

        try {
            $data = sanitizar($_POST);

            if (empty($data['proyecto_id']) || empty($data['usuario_id'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'Faltan campos requeridos',
                    'detalle' => 'Debe proporcionar proyecto_id y usuario_id'
                ]);
                return;
            }

            // Validar que no exista ya la asignación
            $existe = ProyectoPersona::where([
                ['proyecto_id', $data['proyecto_id']],
                ['usuario_id', $data['usuario_id']]
            ]);

            if (!empty($existe)) {
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'Ya existe la asignación'
                ]);
                return;
            }

            $asignacion = new ProyectoPersona($data);
            $resultado = $asignacion->crear();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuario asignado correctamente al proyecto',
                'data' => $resultado
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al asignar persona',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    /**
     * Elimina la asignación de un usuario a un proyecto (API).
     * Requiere proyecto_id y usuario_id en el body.
     */
    public static function eliminarAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();
        header('Content-Type: application/json');

        try {
            $data = sanitizar($_POST);

            if (empty($data['proyecto_id']) || empty($data['usuario_id'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'Faltan campos requeridos'
                ]);
                return;
            }

            $asignacion = ProyectoPersona::where([
                ['proyecto_id', $data['proyecto_id']],
                ['usuario_id', $data['usuario_id']]
            ]);

            if (empty($asignacion)) {
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'La asignación no existe'
                ]);
                return;
            }

            $registro = (array) $asignacion[0];
            $obj = new ProyectoPersona($registro);
            $obj->eliminar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asignación eliminada correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar asignación',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    /**
     * Lista todos los usuarios asignados a un proyecto.
     * Requiere proyecto_id como parámetro GET.
     */
    public static function listarAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();
        header('Content-Type: application/json');

        try {
            $proyecto_id = $_GET['proyecto_id'] ?? null;

            if (!$proyecto_id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'Debe proporcionar un ID de proyecto válido'
                ]);
                return;
            }

            $asignados = ProyectoPersona::joinWhere(
                joins: [['usuarios', 'proyecto_persona.usuario_id', 'usuarios.id']],
                conditions: [['proyecto_id', $proyecto_id]],
                orderBy: 'usuarios.nombre',
                select: 'proyecto_persona.*, usuarios.nombre as usuario_nombre, usuarios.email'
            );

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asignaciones obtenidas',
                'datos' => $asignados
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener asignaciones',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    /**
     * Retorna los usuarios que no están asignados a un proyecto
     * @param Router $router
     * @return void
     */
    public static function listarDisponiblesAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();
        header('Content-Type: application/json');

        try {
            $proyecto_id = $_GET['proyecto_id'] ?? null;

            if (!$proyecto_id) {
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'Falta el ID del proyecto'
                ]);
                return;
            }

            $db = ProyectoPersona::getDB();

            $sql = "SELECT id, nombre, email
                    FROM usuarios
                    WHERE id NOT IN (
                        SELECT usuario_id
                        FROM proyecto_persona
                        WHERE proyecto_id = ?
                    )
                    ORDER BY nombre";

            $stmt = $db->prepare($sql);
            $stmt->execute([$proyecto_id]);
            $usuarios = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios disponibles',
                'datos' => $usuarios
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al consultar usuarios',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}
