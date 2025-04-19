<?php

namespace Controllers;

use Model\Cuenta;
use Model\Persona;
use Model\Tercero;
use Model\Transaccion;
use MVC\Router;
use Exception;

/**
 * Controlador de la aplicación principal
 */
class TransferenciaController
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
        $terceros = Tercero::joinWhere(
            [['cuentas', 'cte_cuenta_id', 'cue_id']],
            [['cte_usuario_id', $_SESSION['user']->id]],
            null,
            'terceros.*, cue_numero'
        );
        $router->render('transferencia/index', [
            'terceros' => $terceros
        ]);
    }


    public static function buscarAPI()
    {
        getHeadersApi();
        hasPermissionApi(['USUARIO']);

        try {
            $cuentas = Cuenta::joinWhere(
                [['personas', 'cue_persona_id', 'per_id']],
                [['per_dpi', $_SESSION['user']->dpi]]
            );
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

    public static function transferirAPI()
    {
        hasPermissionApi(['USUARIO']);
        getHeadersApi();


        $db = Transaccion::getDB();
        $db->beginTransaction();
        $data = sanitizar($_POST);

        $tercero = Tercero::where('cte_id', $data['cte_id'])[0];

        $cuentaTercero = Cuenta::where('cue_id', $tercero->cte_cuenta_id)[0];
        $cuentaPropia = Cuenta::where('cue_id', $data['cue_id'])[0];

        if (!$cuentaTercero) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error realizando transacción',
                'detalle' => 'No se encontró la cuentra ingresada'
            ]);
            $db->rollBack();
            exit;
        }
        $personaPropia = Persona::where('per_id', $cuentaPropia->cue_persona_id)[0];
        $personaTercero = Persona::where('per_id', $cuentaTercero->cue_persona_id)[0];

        if ($data['tra_monto'] < 0.01) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error realizando transacción',
                'detalle' => 'Debe ingresar un monto mayor a 0.01'
            ]);
            $db->rollBack();
            exit;
        }

        if ($data['tra_monto'] > $cuentaPropia->cue_saldo) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error realizando transacción',
                'detalle' => 'La cantidad supera el saldo de la cuenta'
            ]);
            $db->rollBack();
            exit;
        }

        if ($data['tra_monto'] > $tercero->cte_monto_maximo) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error realizando transacción',
                'detalle' => 'La cantidad supera al monto máximo de transferencia'
            ]);
            $db->rollBack();
            exit;
        }

        $conteoHoy = Transaccion::sumaDiaria($personaPropia->per_id, $cuentaTercero->cue_id, 'T');

        if ($conteoHoy >= $tercero->cte_max_transacciones) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error realizando transacción',
                'detalle' => 'Ha superado el limite de transacciones diarias a esta cuenta'
            ]);
            $db->rollBack();
            exit;
        }



        try {

            $saldoPropio = $cuentaPropia->cue_saldo - $data['tra_monto'];

            $transaccionPropia = new Transaccion([
                'tra_cuenta' => $cuentaPropia->cue_id,
                'tra_cuenta_destino' => $cuentaTercero->cue_id,
                'tra_tipo' => 'T',
                'tra_monto' => $data['tra_monto'],
            ]);

            $transaccionPropia->crear();

            $cuentaPropia->sincronizar([
                "cue_saldo" => $saldoPropio,
            ]);

            $cuentaPropia->actualizar();


            $saldoTercero = $cuentaTercero->cue_saldo + $data['tra_monto'];

            $transaccionTercero = new Transaccion([
                'tra_cuenta' => $cuentaTercero->cue_id,
                'tra_tipo' => 'I',
                'tra_monto' => $data['tra_monto'],
            ]);

            $transaccionTercero->crear();

            $cuentaTercero->sincronizar([
                "cue_saldo" => $saldoTercero,
            ]);

            $cuentaTercero->actualizar();

            http_response_code(200);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Transferencia realizada correctamente',
                'datos' => ["propia" => $cuentaPropia, "tercero" => $cuentaTercero, "persona" => $personaPropia, "personaTercero" => $personaTercero, "transaccion" => $transaccionPropia],
            ]);

            $db->commit();
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error realizando transferencia',
                'detalle' => $e->getMessage()
            ]);
            $db->rollBack();
            exit;
        }
    }

    public static function detalleAPI()
    {
        getHeadersApi();
        hasPermissionApi(['USUARIO']);

        $cuenta = filter_var($_GET['cuenta'], FILTER_SANITIZE_NUMBER_INT);
        try {
            $cuentas = Transaccion::where('tra_cuenta', $cuenta);
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
}