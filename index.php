<?php

session_start();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// login/password:  admin/admin123

require_once(__DIR__ . '/init.php');

use System\Exceptions\Exc404;
use System\Router;
use Modules\Todo\Module as Todo;
use Modules\User\Module as User;
use System\Modules;

$url = $_GET['systemqueryurl'];
if ($url === '') $url = 'todos';

$httpMethod = $_SERVER['REQUEST_METHOD'];

try {
    $modules = new Modules();
    $modules->add(new Todo);
    $modules->add(new User);
    
    $router = new Router(BASE_URL);
    
    $modules->registerRoutes($router);
    
    $activeRoute = $router->findRoute($url, $httpMethod);

    $className = $activeRoute['class'];
    $c = new $className;
    $c->params = $activeRoute['params'];
    $m = $activeRoute['method'];
    
    $data = $c->$m();
    echo json_encode($data);
} catch (Exc404 $e) {
    echo $e->getMessage();
}