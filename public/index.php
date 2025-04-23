<?php
require_once __DIR__ . '/../includes/app.php';

use Controllers\AdminController;
use Controllers\AuthController;
use Controllers\CajeroController;
use Controllers\CuentaController;
use Controllers\MonitorController;
use Controllers\ProyectoController;
use Controllers\ProyectoPersonaController;
use Controllers\TerceroController;
use Controllers\TransferenciaController;
use MVC\Router;
use Controllers\AppController;

$router = new Router();
//  $router->setBaseURL('/' . $_ENV['APP_NAME']);
//AUTENTICACION
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


//PROYECTOS
$router->get('/proyectos', [ProyectoController::class, 'index']);
$router->get('/api/proyectos', [ProyectoController::class, 'indexAPI']);
$router->post('/api/proyectos/crear', [ProyectoController::class, 'crearAPI']);
$router->post('/api/proyectos/actualizar', [ProyectoController::class, 'actualizarAPI']);
$router->post('/api/proyectos/eliminar', [ProyectoController::class, 'eliminarAPI']);

$router->post('/api/proyectos/asignar', [ProyectoPersonaController::class, 'asignarAPI']);
$router->post('/api/proyectos/eliminar-asignacion', [ProyectoPersonaController::class, 'eliminarAPI']);
$router->get('/api/proyectos/asignados', [ProyectoPersonaController::class, 'listarAPI']);
$router->get('/api/proyectos/usuarios-disponibles', [ProyectoPersonaController::class, 'listarDisponiblesAPI']);
$router->post('/api/proyectos/actualizar-rol', [ProyectoPersonaController::class, 'actualizarRolAPI']);
$router->get('/proyectos/ver', [ProyectoController::class, 'ver']);


$router->comprobarRutas();
