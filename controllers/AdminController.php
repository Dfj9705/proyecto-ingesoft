<?php

namespace Controllers;

use Model\Persona;
use Model\Usuario;
use MVC\Router;
use Exception;

/**
 * Controlador de la aplicación principal
 */
class AdminController
{
    /**
     * Método para renderizar pagina principal
     * @param \MVC\Router $router
     * @return void
     */
    public static function index(Router $router)
    {
        hasPermission(['ADMINISTRADOR']);
        $router->render('admin/cajeros', []);
    }
    public static function monitor(Router $router)
    {
        hasPermission(['ADMINISTRADOR']);
        $router->render('admin/monitor', []);
    }

    public static function guardarCajeroAPI()
    {
        hasPermissionAPI(['ADMINISTRADOR']);
        getHeadersApi();
        $db = Usuario::getDB();
        $db->beginTransaction();
        $data = sanitizar($_POST);
        if ($data['usu_password'] != $data['usu_password2']) {
            http_response_code(response_code: 400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error creando cajero',
                'detalle' => 'Las contraseñas no coinciden'
            ]);
            $db->rollBack();
            exit;
        }

        if (Usuario::existeEmail($data['usu_email'])) {
            http_response_code(response_code: 400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error creando cajero',
                'detalle' => 'El correo ya esta en uso'
            ]);
            $db->rollBack();
            exit;
        }
        try {

            $persona = Persona::findOrCreate(['per_dpi' => $data['per_dpi']], $data);

            $usuario = new Usuario([
                'usu_email' => $data['usu_email'],
                'usu_password' => password_hash($data['usu_password'], PASSWORD_DEFAULT),
                'usu_persona' => $persona->per_id,
                'usu_verificado' => 1,
                'usu_rol' => 2,
            ]);



            $usuario->crear();

            http_response_code(200);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Cajero creado correctamente',
            ]);

            $db->commit();
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error creando cajero',
                'detalle' => $e->getMessage()
            ]);
            $db->rollBack();
            exit;
        }
    }

    public static function buscarCajeroAPI()
    {
        hasPermissionAPI(['ADMINISTRADOR']);
        getHeadersApi();
        try {
            $personas = Usuario::joinWhere(
                [['personas', 'usu_persona', 'per_id']],
                [['usu_rol', 2]]
            );
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => count($personas) . ' persona/s encontradas',
                'datos' => $personas
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error obteniendo personas',
                'detalle' => $e->getMessage(),
            ]);
            exit;
        }
    }

    public static function cambiarEstadoAPI()
    {
        hasPermissionAPI(['ADMINISTRADOR']);
        $_POST['usu_id'] = filter_var($_POST['usu_id'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['usu_estado'] = filter_var($_POST['usu_estado'], FILTER_SANITIZE_NUMBER_INT);

        try {
            $usuario = Usuario::find($_POST['usu_id']);
            $usuario->sincronizar($_POST);
            $usuario->actualizar();
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estado cambiado exitosamente.',
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error cambiando estado',
                'detalle' => $e->getMessage()
            ]);
            exit;
        }
    }

    public static function eliminarAPI()
    {
        hasPermissionAPI(['ADMINISTRADOR']);
        $_POST['usu_id'] = filter_var($_POST['usu_id'], FILTER_SANITIZE_NUMBER_INT);

        try {
            $usuario = Usuario::find($_POST['usu_id']);
            $usuario->eliminar();
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Cajero eliminado exitosamente.',
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error eliminando cajero',
                'detalle' => $e->getMessage()
            ]);
            exit;
        }
    }

}
