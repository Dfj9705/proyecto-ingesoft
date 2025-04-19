<?php

namespace Controllers;

use Model\Cuenta;
use Model\Persona;
use Model\Transaccion;
use MVC\Router;
use Exception;

/**
 * Controlador del panel de cajero
 */
class CajeroController
{
    /**
     * Método para renderizar pagina de cajero
     * @param \MVC\Router $router
     * @return void
     */
    public static function index(Router $router)
    {
        hasPermission(['CAJERO']);
        $router->render('cajero/index', []);
    }

    public static function transaccionAPI()
    {
        hasPermissionApi(['CAJERO']);
        getHeadersApi();

        $db = Transaccion::getDB();
        $db->beginTransaction();
        $data = sanitizar($_POST);

        $cuenta = Cuenta::where('cue_numero', $data['cue_numero'])[0];

        if (!$cuenta) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error realizando transacción',
                'detalle' => 'No se encontró la cuentra ingresada'
            ]);
            $db->rollBack();
            exit;
        }
        $persona = Persona::where('per_id', $cuenta->cue_persona_id)[0];

        if ($data['tra_monto'] < 1) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error realizando transacción',
                'detalle' => 'Debe ingresar un monto mayor a 0'
            ]);
            $db->rollBack();
            exit;
        }

        if ($data['tra_tipo'] == 'R' && $data['tra_monto'] > $cuenta->cue_saldo) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error realizando transacción',
                'detalle' => 'La cantidad supera el saldo de la cuenta'
            ]);
            $db->rollBack();
            exit;
        }



        try {

            $saldo = $data['tra_tipo'] == 'D' ? $cuenta->cue_saldo + $data['tra_monto'] : $cuenta->cue_saldo - $data['tra_monto'];

            $transaccion = new Transaccion([
                'tra_cuenta' => $cuenta->cue_id,
                'tra_tipo' => $data['tra_tipo'],
                'tra_monto' => $data['tra_monto'],
            ]);

            $transaccion->crear();

            $cuenta->sincronizar([
                "cue_saldo" => $saldo,
            ]);

            $cuenta->actualizar();

            http_response_code(200);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Transacción registrada correctamente',
                'datos' => ["cuenta" => $cuenta, "persona" => $persona, "transaccion" => $transaccion],
            ]);

            $db->commit();
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error realizando transaccion',
                'detalle' => $e->getMessage()
            ]);
            $db->rollBack();
            exit;
        }
    }

    public static function buscarCuentaAPI()
    {
        hasPermissionApi(['CAJERO']);
        getHeadersApi();
        $numero = htmlspecialchars($_GET['cuenta']);
        try {
            $cuenta = Cuenta::joinWhere(
                [['personas', 'cue_persona_id', 'per_id']],
                [['cue_numero', $numero]],
            );
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => count($cuenta) . ' cuenta encontradas',
                'datos' => $cuenta
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error obteniendo cuenta',
                'detalle' => $e->getMessage(),
            ]);
            exit;
        }
    }

}