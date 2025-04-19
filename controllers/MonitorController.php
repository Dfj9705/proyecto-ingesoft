<?php

namespace Controllers;

use Model\Cuenta;
use Model\Persona;
use Model\Transaccion;
use Model\Usuario;
use MVC\Router;
use Exception;

/**
 * Controlador de la aplicación principal
 */
class MonitorController
{

    public static function monitor(Router $router)
    {
        hasPermission(['ADMINISTRADOR']);
        $router->render('admin/monitor', []);
    }

    public static function buscarCuentasAPI()
    {
        getHeadersApi();
        hasPermissionApi(['ADMINISTRADOR']);
        $tipo = $_GET['tipo'] != '' ? filter_var($_GET['tipo'], FILTER_SANITIZE_NUMBER_INT) : 1;
        try {
            $conteo = Cuenta::conteo($tipo);
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Cuentas obtenidas',
                'datos' => $conteo
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error obteniendo cuentas',
                'detalle' => $e->getMessage(),
            ]);
            exit;
        }
    }


    public static function buscarUsuariosAPI()
    {
        getHeadersApi();
        hasPermissionApi(['ADMINISTRADOR']);
        $tipo = $_GET['tipo'] != '' ? filter_var($_GET['tipo'], FILTER_SANITIZE_NUMBER_INT) : 1;
        try {
            $conteo = Usuario::conteo($tipo);
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios obtenidos',
                'datos' => $conteo
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error obteniendo usuarios',
                'detalle' => $e->getMessage(),
            ]);
            exit;
        }
    }

    public static function buscarTransaccionesAPI()
    {
        getHeadersApi();
        hasPermissionApi(['ADMINISTRADOR']);

        $tipo = $_GET['tipo'] != '' ? filter_var($_GET['tipo'], FILTER_SANITIZE_NUMBER_INT) : 1;
        $movimiento = $_GET['movimiento'] != '' ? htmlspecialchars($_GET['movimiento']) : '';
        try {
            $conteo = Transaccion::conteo($tipo, $movimiento);
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Transacciones obtenidos',
                'datos' => $conteo
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error obteniendo transacciones',
                'detalle' => $e->getMessage(),
            ]);
            exit;
        }
    }

    public static function buscarSumasAPI()
    {
        getHeadersApi();
        hasPermissionApi(['ADMINISTRADOR']);

        $tipo = $_GET['tipo'] != '' ? filter_var($_GET['tipo'], FILTER_SANITIZE_NUMBER_INT) : 1;

        try {
            $depositos = Transaccion::suma($tipo, 'D');
            $retiros = Transaccion::suma($tipo, 'R');
            $transferencias = Transaccion::suma($tipo, 'T');
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Transacciones obtenidos',
                'datos' => [
                    $depositos,
                    $retiros,
                    $transferencias,
                ]
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error obteniendo transacciones',
                'detalle' => $e->getMessage(),
            ]);
            exit;
        }
    }
}