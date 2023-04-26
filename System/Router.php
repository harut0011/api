<?php

namespace System;

use System\Exceptions\Exc404;

class Router
{
    protected string $basePath = '';
    protected array $routes = [];

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    public function addRoute(string $type, string $pattern, $class, string $method = 'index', array $params = []): void
    {
        $this->routes[] = [
            'type' => $type,
            'pattern' => $pattern,
            'class' => $class,
            'method' => $method,
            'params' => $params
        ];
    }

    public function findRoute(string $url, string $httpMethod)
    {
        $activeRoute = null;

        foreach ($this->routes as $route) {
            if (preg_match($route['pattern'], $url, $matches) && strtoupper($httpMethod) === strtoupper($route['type'])) {
                $activeRoute['params'] = [];

                foreach ($route['params'] as $k => $v) {
                    if (isset($matches[$k])) {
                        $activeRoute['params'][$v] = $matches[$k];
                    }
                }

                $activeRoute += $route;
            }
        }

        
        if ($activeRoute === null) {
            throw new Exc404('Page not found');
        }

        return $activeRoute;
    }
}