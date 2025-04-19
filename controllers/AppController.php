<?php

namespace Controllers;

use MVC\Router;
use Exception;

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
        $router->render('pages/index', []);
    }

}
