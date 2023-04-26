<?php

namespace Modules\Todo;

use System\Router;

class Module
{
    public function registerRoutes(Router $router)
    {
        $router->addRoute('GET', '/^todos$/', Controller::class);
        $router->addRoute('GET', '/^todos\/([0-9]+)$/', Controller::class, 'one', [
            1 => 'id',
        ]);
        
        $router->addRoute('POST', '/^todos\/add$/', Controller::class, 'add');

        $router->addRoute('DELETE', '/^todos\/delete\/([0-9]+)$/', Controller::class, 'delete', [
            1 => 'id',
        ]);

        $router->addRoute('PATCH', '/^todos\/edit\/([0-9]+)$/', Controller::class, 'edit', [
            1 => 'id',
            2 => 'fields',
        ]);
    }
}