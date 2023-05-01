<?php

namespace Modules\User;

use System\Router;

class Module
{
    public function registerRoutes(Router $router)
    {
        $router->addRoute('POST', '/^users\/signup$/', Controller::class, 'signUp', [
            1 => 'fields',
        ]);
        $router->addRoute('POST', '/^users\/signin$/', Controller::class, 'signIn', [
            1 => 'fields'
        ]);
    }
}