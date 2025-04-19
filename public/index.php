<?php
require_once __DIR__ . '/../includes/app.php';

use Controllers\AdminController;
use Controllers\AuthController;
use Controllers\CajeroController;
use Controllers\CuentaController;
use Controllers\MonitorController;
use Controllers\TerceroController;
use Controllers\TransferenciaController;
use MVC\Router;
use Controllers\AppController;

$router = new Router();
//  $router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class, 'index']);
$router->get('/registro', [AuthController::class, 'registro']);
$router->get('/login', [AuthController::class, 'login']);
$router->get('/envio', [AuthController::class, 'envio']);
$router->post('/envio', [AuthController::class, 'envioAPI']);
$router->get('/cambio', [AuthController::class, 'cambio']);
$router->post('/cambio', [AuthController::class, 'cambioAPI']);
$router->get('/no-verificado', [AuthController::class, 'noVerificado']);
$router->get('/reenviar', [AuthController::class, 'reenviar']);
$router->get('/verificar', [AuthController::class, 'verificar']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->post('/login', [AuthController::class, 'loginAPI']);
$router->post('/registro', [AuthController::class, 'registroAPI']);



$router->comprobarRutas();
