<?php

namespace Controllers;

use Classes\Email;
use Model\Cuenta;
use Model\Usuario;
use MVC\Router;
use Exception;

/**
 * Controlador de autenticación
 */
class AuthController
{
    public static function registro(Router $router)
    {
        isNotAuth();
        $router->render('auth/registro', [], 'layouts/auth');
    }
    public static function cambio(Router $router)
    {
        isNotAuth();
        $token = htmlspecialchars($_GET['t']);
        $usuario = Usuario::where('usu_token', $token)[0];

        if ($usuario && $token != '') {
            $usuario->usu_token = '';
            $usuario->actualizar();
            $router->render('auth/cambio', [
                'email' => $usuario->usu_email,
                'id' => $usuario->usu_id,
            ], 'layouts/auth');
        } else {
            $router->render('auth/error', [
                'codigo' => 401,
                'mensaje' => 'Token invalido',
                'detalle' => 'El token ingresado no es válido'
            ], 'layouts/auth');
        }
    }
    public static function login(Router $router)
    {
        isNotAuth();
        $router->render('auth/login', [], 'layouts/auth');
    }
    public static function envio(Router $router)
    {
        isNotAuth();
        $router->render('auth/envio', [], 'layouts/auth');
    }

    public static function noVerificado(Router $router)
    {
        isAuth();
        isNotVerified();
        $router->render('auth/no-verificado', [], 'layouts/auth');
    }
    public static function registroAPI(Router $router)
    {
        isNotAuthApi();
        getHeadersApi();
        $db = Usuario::getDB();
        $db->beginTransaction();
        $data = sanitizar($_POST);
        $cuenta = Cuenta::joinWhere(
            [['personas', 'cue_persona_id', 'per_id']],
            [['cue_numero', $data['cuenta']]],
        )[0];

        if (!$cuenta) {
            http_response_code(response_code: 400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error al registrarse',
                'detalle' => 'La cuenta ingresada no existe'
            ]);
            $db->rollBack();
            exit;
        }

        if ($cuenta->per_dpi != $data['dpi']) {
            http_response_code(response_code: 400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error al registrarse',
                'detalle' => 'La cuenta ingresada no coincide con el DPI ingresado'
            ]);
            $db->rollBack();
            exit;
        }

        if ($data['password'] != $data['password2']) {
            http_response_code(response_code: 400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error al registrarse',
                'detalle' => 'Las contraseñas no coinciden'
            ]);
            $db->rollBack();
            exit;
        }

        if (Usuario::existeEmail($data['correo'])) {
            http_response_code(response_code: 400);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Error al registrarse',
                'detalle' => 'El correo ya esta en uso'
            ]);
            $db->rollBack();
            exit;
        }
        try {
            $token = uniqid();
            $usuario = new Usuario([
                'usu_email' => $data['correo'],
                'usu_password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'usu_persona' => $cuenta->per_id,
                'usu_token' => $token,
                'usu_rol' => 3,
                'usu_estado' => 1,
            ]);

            $email = new Email();
            $html = $router->load('email/registro', [
                'nombre' => $cuenta->per_nombre1,
                'token' => $token,
            ]);
            $enviado = $email->generateEmail("Registro exitoso", [$usuario->usu_email], $html)->send();
            if ($enviado) {

                $usuario->crear();

                http_response_code(200);

                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Registro exitoso',
                ]);

                $db->commit();
                exit;
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrarse',
                    'detalle' => 'Correo no enviado'
                ]);
                $db->rollBack();
                exit;
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrarse',
                'detalle' => $e->getMessage()
            ]);
            $db->rollBack();
            exit;
        }
    }

    public static function loginAPI(Router $router)
    {
        isNotAuthApi();
        getHeadersApi();
        $db = Usuario::getDB();
        $db->beginTransaction();

        $data = sanitizar($_POST);

        try {
            $correo = $data['correo'];
            $password = $data['password'];
            $rol = $data['rol'];

            $usuario = Usuario::joinWhere([['personas', 'per_id', 'usu_persona']], [['usu_email', $correo], ['usu_rol', $rol], ['usu_estado', 1]], null, "usu_id as id, usu_email as correo, per_dpi as dpi, usu_rol as rol, usu_password as password , per_nombre1 as nombre, per_apellido1 as apellido, usu_verificado as verificado")[0];


            if (!$usuario) {
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'Usuario no encontrado o desactivado',
                    'detalle' => 'Revise la información ingresada'
                ]);
                $db->rollBack();
                exit;
            }

            // echo json_encode($usuario);
            // $db->rollBack();
            // exit;

            if (password_verify($password, $usuario->password)) {
                unset($usuario->password);
                switch ($rol) {
                    case '1':
                        $usuario->rol = "ADMINISTRADOR";
                        break;
                    case '2':
                        $usuario->rol = "CAJERO";
                        break;
                    case '3':
                        $usuario->rol = "USUARIO";
                        break;
                }
                $_SESSION['auth'] = true;
                $_SESSION['user'] = $usuario;
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Autenticación correcta',
                ]);
                $db->commit();
            } else {
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'Credenciales incorrectas',
                ]);
                $db->rollBack();
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error obteniendo usuario',
                'detalle' => $e->getMessage()
            ]);
            $db->rollBack();
            exit;
        }
    }

    public static function logout()
    {
        isAuth();
        $_SESSION = [];
        session_destroy();
        header('location: /login');
    }

    public static function reenviar(Router $router)
    {
        $db = Usuario::getDB();
        $db->beginTransaction();

        try {

            $token = uniqid();
            $usuario = Usuario::find($_SESSION['user']->id);
            $usuario->sincronizar([
                "usu_token" => $token,
            ]);
            $usuario->actualizar();
            $email = new Email();
            $html = $router->load('email/registro', [
                'nombre' => $_SESSION['user']->nombre,
                'token' => $token,
            ]);
            $enviado = $email->generateEmail("Verificar correo", [$usuario->usu_email], $html)->send();
            if ($enviado) {

                http_response_code(200);

                $_SESSION['mensaje'] = "Revise su bandeja de entrada";


                $db->commit();
            } else {
                http_response_code(500);
                $_SESSION['mensaje'] = "Correo no enviado";
                $db->rollBack();

            }


        } catch (Exception $e) {
            $_SESSION['mensaje'] = $e->getMessage();
            $db->rollBack();
        }

        header("location: no-verificado");
    }

    public static function verificar(Router $router)
    {
        $token = $_GET['t'];

        try {
            $usuario = Usuario::where('usu_token', $token)[0];

            if ($usuario) {
                $usuario->sincronizar([
                    'usu_verificado' => 1,
                    'usu_token' => ''
                ]);

                $usuario->actualizar();
                if (isset($_SESSION['user'])) {
                    $_SESSION['user']->verificado = 1;
                }
                header("location: /login");
                http_response_code(302);
            } else {
                http_response_code(401);
                $router->render('auth/error', [
                    'codigo' => 401,
                    'mensaje' => 'Token invalido',
                    'detalle' => 'El token ingresado no es válido'
                ], 'layouts/auth');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "detalle" => $e->getMessage(),
                "mensaje" => "Error de conexión bd",
                "codigo" => 5,
            ]);
        }
    }

    public static function envioAPI(Router $router)
    {
        isNotAuth();
        getHeadersApi();
        try {
            $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);

            $consulta = Usuario::where("usu_email", $correo, "=");
            $usuario = (array) $consulta[0];
            if (count($consulta) > 0) {
                if ($usuario['usu_estado'] == 1 && $usuario['usu_verificado'] == 1) {
                    $uniId = uniqid();
                    $usuarioObj = new Usuario($usuario);
                    $usuarioObj->usu_token = $uniId;
                    $usuarioObj->actualizar();

                    $email = new Email();
                    $html = $router->load('email/cambio', ['token' => $uniId]);
                    $enviado = $email->generateEmail("SOLICITUD DE CAMBIO DE CONTRASEÑA", [$usuarioObj->usu_email], $html)->send();

                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Se ha enviado un correo a dirección especificada',
                    ]);
                    exit;
                } else {
                    echo json_encode([
                        'codigo' => 2,
                        'mensaje' => 'Su usuario no se encuentra activo o no ha sido verificado',
                    ]);
                    exit;
                }
            } else {
                echo json_encode([
                    'codigo' => 2,
                    'mensaje' => 'No se ha encontrado usuario con el correo proporcionado',
                ]);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error enviando correo',
                'detalle' => $e->getMessage()
            ]);
            exit;
        }
    }

    public static function cambioAPI(Router $router)
    {
        isNotAuth();
        getHeadersApi();

        $db = Usuario::getDB();
        $_POST['usu_id'] = base64_decode($_POST['usu_id']);
        $db->beginTransaction();
        $data = sanitizar($_POST);

        if ($data['password2'] != $data['password']) {
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Las contraseñas no coinciden',
            ]);
            $db->rollBack();
            exit;
        }

        try {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $usuario = Usuario::find($data['usu_id']);
            $usuario->sincronizar([
                'usu_password' => $data['password']
            ]);

            $actualizado = $usuario->actualizar();


            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Cambio de contraseña exitoso',
            ]);


            $db->commit();
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error cambiando contraseña',
                'detalle' => $e->getMessage()
            ]);
            $db->rollBack();
            exit;
        }
    }
}
