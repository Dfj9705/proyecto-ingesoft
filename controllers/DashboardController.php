<?php

namespace Controllers;

use Exception;
use Model\Tarea;
use Model\Proyecto;
use MVC\Router;

/**
 * Controlador para estadísticas generales del dashboard
 */
class DashboardController
{
    /**
     * Método para renderizar dasboard general
     * @param \MVC\Router $router
     * @return void
     */
    public static function index(Router $router)
    {
        $router->render('index');
    }
    /**
     * Estadísticas: tareas agrupadas por estado en todos los proyectos
     */
    public static function tareasPorEstadoGeneralAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();
        header('Content-Type: application/json');

        try {
            $db = Tarea::getDB();
            $sql = "SELECT estado, COUNT(*) as total FROM tareas GROUP BY estado";
            $stmt = $db->query($sql);
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $estados = [];
            foreach ($result as $row) {
                $estados[$row['estado']] = (int) $row['total'];
            }

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Tareas agrupadas por estado',
                'estados' => $estados
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener tareas por estado',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    /**
     * Estadísticas: tareas asignadas por usuario en todos los proyectos
     */
    public static function tareasPorUsuarioGeneralAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();
        header('Content-Type: application/json');

        try {
            $db = Tarea::getDB();
            $sql = "SELECT usuarios.nombre as usuario, COUNT(*) as total
                    FROM tareas
                    LEFT JOIN usuarios ON tareas.asignado_a = usuarios.id
                    GROUP BY tareas.asignado_a, usuarios.nombre
                    ORDER BY total DESC";
            $stmt = $db->query($sql);
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Tareas agrupadas por usuario',
                'datos' => $result
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener tareas por usuario',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    /**
     * Estadísticas: tareas agrupadas por proyecto
     */
    public static function tareasPorProyectoGeneralAPI(Router $router)
    {
        isAuthApi();
        getHeadersApi();
        header('Content-Type: application/json');

        try {
            $db = Tarea::getDB();
            $sql = "SELECT proyectos.nombre as proyecto, COUNT(*) as total
                    FROM tareas
                    LEFT JOIN proyectos ON tareas.proyecto_id = proyectos.id
                    GROUP BY tareas.proyecto_id, proyectos.nombre
                    ORDER BY total DESC";
            $stmt = $db->query($sql);
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Tareas agrupadas por proyecto',
                'datos' => $result
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener tareas por proyecto',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}
