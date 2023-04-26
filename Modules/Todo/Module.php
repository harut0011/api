<?php

namespace Modules\Todo;

use System\Router;

class Module
{
    public function registerRoutes(Router $router)
    {
        $router->get('/^todos$/', Controller::class);
        $router->get('/^todos\/([0-9]+)$/', Controller::class, 'item', [
            1 => 'id'
        ]);
    }
}