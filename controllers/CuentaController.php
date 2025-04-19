<?php

namespace Controllers;

use Model\Cuenta;
use Model\Persona;
use Model\Transaccion;
use MVC\Router;
use Exception;

/**
 * Controlador de la aplicación principal
 */
class CuentaController
{
    /**
     * Método para renderizar pagina principal
     * @param \MVC\Router $router
     * @return void
     */
    public static function index(Router $router)
    {
        hasPermission(['CAJERO']);
        $router->render('cuentas/index', []);
    }

    public static function guardarAPI(Router $router)
    {
        hasPermissionApi(['CAJERO']);
        getHeadersApi();

        $db = Cuenta::getDB();
        $db->beginTransaction();
        $data = sanitizar($_POST);

        if ($data['monto'] < 100) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error creando cuenta',
                'detalle' => 'Debe ingresar un monto mayor a 100'
            ]);
            $db->rollBack();
            exit;
        }

        try {

            $persona = Persona::findOrCreate(['per_dpi' => $data['per_dpi']], $data);
            $numero = Cuenta::numeroCuenta();
            $cuenta = new Cuenta([
                'cue_persona_id' => $persona->per_id,
                'cue_tipo' => $data['cue_tipo'],
                'cue_numero' => $numero,
                'cue_saldo' => $data['monto']
            ]);
            $resultado = $cuenta->crear();


            $transaccion = new Transaccion([
                'tra_cuenta' => $resultado['id'],
                'tra_tipo' => 'D',
                'tra_monto' => $data['monto'],
            ]);

            $transaccion->crear();

            http_response_code(200);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Cuenta registrada correctamente',
            ]);

            $db->commit();
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error creando cuenta',
                'detalle' => $e->getMessage()
            ]);
            $db->rollBack();
            exit;
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        hasPermissionApi(['CAJERO']);

        try {
            $personas = Persona::all();
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
    public static function detalleCuentaAPI()
    {
        getHeadersApi();
        hasPermissionApi(['CAJERO']);

        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        try {
            $cuentas = Cuenta::where('cue_persona_id', $id);
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => count($cuentas) . ' cuenta/s encontradas',
                'datos' => $cuentas
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

    public static function modificarAPI(Router $router)
    {

        hasPermissionApi(['CAJERO']);
        getHeadersApi();

        $db = Persona::getDB();
        $db->beginTransaction();
        $data = sanitizar($_POST);


        try {

            $persona = Persona::find($data['per_id']);
            $persona->sincronizar($data);
            $persona->actualizar();

            $db->commit();

            http_response_code(200);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Persona modificada correctamente',
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error modificando persona',
                'detalle' => $e->getMessage()
            ]);
            $db->rollBack();
            exit;
        }
    }

    public static function eliminarPersonaAPI(Router $router)
    {

        hasPermissionApi(['CAJERO']);
        getHeadersApi();

        $db = Persona::getDB();
        $db->beginTransaction();
        $data = sanitizar($_POST);


        try {

            $persona = Persona::find($data['per_id']);
            $persona->eliminar();

            $db->commit();

            http_response_code(200);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Persona eliminada correctamente',
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error eliminado persona',
                'detalle' => $e->getMessage()
            ]);
            $db->rollBack();
            exit;
        }
    }
    public static function eliminarAPI(Router $router)
    {
        hasPermissionApi(['CAJERO']);

        // isNotAuthApi();
        getHeadersApi();

        $db = Cuenta::getDB();
        $db->beginTransaction();
        $data = sanitizar($_POST);


        try {

            $persona = Cuenta::find($data['cue_id']);
            $persona->eliminar();

            $db->commit();

            http_response_code(200);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Cuenta eliminada correctamente',
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error eliminado cuenta',
                'detalle' => $e->getMessage()
            ]);
            $db->rollBack();
            exit;
        }
    }

    public static function buscarPersonaAPI()
    {
        getHeadersApi();
        hasPermissionApi(['CAJERO', 'ADMINISTRADOR']);

        $dpi = filter_var($_GET['dpi'], FILTER_SANITIZE_NUMBER_INT);
        try {
            $persona = Persona::where('per_dpi', $dpi);
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => count($persona) . ' persona encontradas',
                'datos' => $persona
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error obteniendo persona',
                'detalle' => $e->getMessage(),
            ]);
            exit;
        }
    }

}
