<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once(__DIR__ . '/init.php');

use System\Exceptions\Exc404;
use System\Router;
use Modules\Todo\Module as Todo;
use System\Modules;

$url = $_GET['systemqueryurl'];
if ($url === '') $url = 'todos';

try {
    $modules = new Modules();
    $modules->add(new Todo);
    
    $router = new Router(BASE_URL);
    
    $modules->registerRoutes($router);
    
    $activeRoute = $router->findRoute($url);

    $className = $activeRoute['class'];
    $c = new $className;
    $m = $activeRoute['method'];
    
    $c->$m();
} catch (Exc404 $e) {
    echo $e->getMessage();
}