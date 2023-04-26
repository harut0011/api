<?php

namespace System;

class Modules
{
    protected Router $router;
    protected array $modules;

    public function add($module): void
    {
        $this->modules[] = $module;
    }
    
    public function registerRoutes(Router $router): void
    {
        foreach ($this->modules as $module) {
            $module->registerRoutes($router);
        }
    }
}