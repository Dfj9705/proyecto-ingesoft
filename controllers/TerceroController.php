<?php

namespace Controllers;

use Model\Cuenta;
use Model\Persona;
use Model\Tercero;
use Model\Usuario;
use MVC\Router;
use Exception;

/**
 * Controlador de la aplicación principal
 */
class TerceroController
{
    /**
     * Método para renderizar pagina principal
     * @param \MVC\Router $router
     * @return void
     */
    public static function index(Router $router)
    {
        hasPermission(['USUARIO']);
        $router->render('terceros/index', []);
    }

    public static function buscarCuentaAPI()
    {
        hasPermissionApi(['USUARIO']);
        getHeadersApi();
        $numero = htmlspecialchars($_GET['cuenta']);
        try {
            $cuenta = Cuenta::joinWhere(
                [['personas', 'cue_persona_id', 'per_id']],
                [['cue_numero', $numero], ['per_dpi', $_SESSION['user']->dpi, '!=']],
                null,
                'per_nombre1, per_nombre2, per_apellido1, per_apellido2'
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

    public static function guardarAPI(Router $router)
    {
        hasPermissionApi(['USUARIO']);
        getHeadersApi();

        $db = Tercero::getDB();
        $db->beginTransaction();
        $data = sanitizar($_POST);


        if ($data['cte_monto_maximo'] < 1) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error agregando cuenta',
                'detalle' => 'Debe ingresar un monto mayor a 0'
            ]);
            $db->rollBack();
            exit;
        }
        if ($data['cte_max_transacciones'] < 1) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error agregando cuenta',
                'detalle' => 'Debe ingresar un cantidad de transacciones mayor a 0'
            ]);
            $db->rollBack();
            exit;
        }


        try {

            $cuenta = Cuenta::joinWhere(
                [['personas', 'cue_persona_id', 'per_id']],
                [['cue_numero', $data['cue_numero']]]
            )[0];
            if ($cuenta->per_dpi == $_SESSION['user']->dpi) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'Error agregando cuenta',
                    'detalle' => 'Debe agregar una cuenta de otra persona'
                ]);
                $db->rollBack();
                exit;
            }

            if (Tercero::existeTercero($cuenta->cue_id)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'Error agregando cuenta',
                    'detalle' => 'Ya ha agregado esta cuenta con anterioridad'
                ]);
                $db->rollBack();
                exit;
            }

            $tercero = new Tercero([
                'cte_usuario_id' => $_SESSION['user']->id,
                'cte_cuenta_id' => $cuenta->cue_id,
                'cte_alias' => $data['cte_alias'],
                'cte_monto_maximo' => $data['cte_monto_maximo'],
                'cte_max_transacciones' => $data['cte_max_transacciones'],
            ]);
            $resultado = $tercero->crear();

            http_response_code(200);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Cuenta agregada correctamente',
            ]);

            $db->commit();
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error agregando cuenta',
                'detalle' => $e->getMessage()
            ]);
            $db->rollBack();
            exit;
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        hasPermissionApi(['USUARIO']);

        try {
            $cuentas = Tercero::joinWhere(
                [['cuentas', 'cte_cuenta_id', 'cue_id']],
                [['cte_usuario_id', $_SESSION['user']->id]]
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

    public static function modificarAPI(Router $router)
    {
        hasPermissionApi(['USUARIO']);
        getHeadersApi();

        $db = Tercero::getDB();
        $db->beginTransaction();
        $data = sanitizar($_POST);


        if ($data['cte_monto_maximo'] < 1) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error agregando cuenta',
                'detalle' => 'Debe ingresar un monto mayor a 0'
            ]);
            $db->rollBack();
            exit;
        }
        if ($data['cte_max_transacciones'] < 1) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error agregando cuenta',
                'detalle' => 'Debe ingresar un cantidad de transacciones mayor a 0'
            ]);
            $db->rollBack();
            exit;
        }


        try {


            $tercero = Tercero::find($data['cte_id']);
            $tercero->sincronizar([
                'cte_alias' => $data['cte_alias'],
                'cte_monto_maximo' => $data['cte_monto_maximo'],
                'cte_max_transacciones' => $data['cte_max_transacciones'],
            ]);
            $resultado = $tercero->actualizar();

            http_response_code(200);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Cuenta modificada correctamente',
            ]);

            $db->commit();
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error modificando cuenta',
                'detalle' => $e->getMessage()
            ]);
            $db->rollBack();
            exit;
        }
    }

    public static function eliminarAPI(Router $router)
    {
        hasPermissionApi(['USUARIO']);

        // isNotAuthApi();
        getHeadersApi();

        $db = Tercero::getDB();
        $db->beginTransaction();
        $data = sanitizar($_POST);


        try {

            $tercero = Tercero::find($data['cte_id']);
            $tercero->eliminar();

            $db->commit();

            http_response_code(200);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Cuenta de tercero eliminada correctamente',
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error eliminado cuenta de tercero',
                'detalle' => $e->getMessage()
            ]);
            $db->rollBack();
            exit;
        }
    }
}
