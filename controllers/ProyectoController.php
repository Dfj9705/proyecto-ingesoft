<?php

namespace Controllers;

use Model\ProyectoPersona;
use MVC\Router;
use Model\Proyecto;
use Exception;

/**
 * Controlador del módulo de proyectos en formato API
 */
class ProyectoController
{
    /**
     * Renderiza la vista principal del módulo de proyectos
     * @param Router $router Instancia del enrutador MVC
     */
    public static function index(Router $router)
    {
        isAuth();
        isVerified();

        $router->render('proyectos/index', []);
    }

    /**
     * Retorna todos los proyectos en formato JSON
     */
    public static function indexAPI()
    {
        isAuthApi();
        getHeadersApi();

        try {
            $usuario_id = $_SESSION['user']->id ?? null;

            if (!$usuario_id) {
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'Usuario no autenticado'
                ]);
                return;
            }

            $sql = "SELECT * FROM proyectos 
                    WHERE creado_por = ?
                    ORDER BY fecha_inicio DESC";

            $db = ProyectoPersona::getDB(); // o Proyecto::getDB() si está disponible
            $stmt = $db->prepare($sql);
            $stmt->execute([$usuario_id]);
            $proyectos = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Proyectos obtenidos correctamente',
                'datos' => $proyectos
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error obteniendo proyectos',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    /**
     * Crea un nuevo proyecto en la base de datos
     */
    public static function crearAPI()
    {
        isAuthApi();
        getHeadersApi();

        $db = Proyecto::getDB();
        $db->beginTransaction();

        try {
            $data = sanitizar($_POST);
            $data['creado_por'] = $_SESSION['user']->id ?? null;

            $proyecto = new Proyecto($data);
            $resultado = $proyecto->crear();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Proyecto creado exitosamente'
                ]);
                $db->commit();
            } else {
                throw new Exception('No se pudo crear el proyecto');
            }
        } catch (Exception $e) {
            $db->rollBack();
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al crear proyecto',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    /**
     * Actualiza un proyecto existente
     */
    public static function actualizarAPI()
    {
        isAuthApi();
        getHeadersApi();
        $db = Proyecto::getDB();
        $db->beginTransaction();

        try {
            $data = sanitizar($_POST);
            $proyecto = Proyecto::find($data['id']);
            if (!$proyecto)
                throw new Exception('Proyecto no encontrado');

            $proyecto->sincronizar($data);
            $proyecto->actualizar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Proyecto actualizado correctamente'
            ]);
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error actualizando proyecto',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    /**
     * Elimina un proyecto
     */
    public static function eliminarAPI()
    {
        isAuthApi();
        getHeadersApi();
        $db = Proyecto::getDB();
        $db->beginTransaction();

        try {
            $id = $_POST['id'] ?? null;
            $proyecto = Proyecto::find($id);
            if (!$proyecto)
                throw new Exception('Proyecto no encontrado');

            $proyecto->eliminar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Proyecto eliminado correctamente'
            ]);
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar proyecto',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}
