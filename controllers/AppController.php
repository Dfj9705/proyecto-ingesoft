<?php

namespace Controllers;

use Classes\PrioridadML;
use Model\ProyectoPersona;
use MVC\Router;
use Exception;
use PDO;

/**
 * Controlador de la aplicación principal
 */
class AppController
{
    /**
     * Método para renderizar pagina principal
     * @param \MVC\Router $router
     * @return void
     */
    public static function index(Router $router)
    {
        isAuth();
        isVerified();

        $usuario_id = $_SESSION['user']->id;
        $db = ProyectoPersona::getDB();
        $sql = "
        SELECT p.*, 
               CASE 
                   WHEN p.creado_por = :usuario THEN 'creador'
                   ELSE pp.rol_asignado 
               END as rol_asignado
        FROM proyectos p
        LEFT JOIN proyecto_persona pp 
               ON p.id = pp.proyecto_id AND pp.usuario_id = :usuario
        WHERE p.creado_por = :usuario 
           OR pp.usuario_id = :usuario
        GROUP BY p.id
        ORDER BY p.fecha_inicio DESC
    ";

        $stmt = $db->prepare($sql);
        $stmt->execute(['usuario' => $usuario_id]);
        $proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $router->render('pages/index', [
            'proyectos' => $proyectos
        ]);
    }

    public static function entrenarPrioridad()
    {
        try {
            PrioridadML::entrenar();
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Modelo entrenado correctamente'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al entrenar el modelo',
                'detalle' => $e->getMessage()
            ]);
        }
    }


}
