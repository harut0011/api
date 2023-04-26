<?php

const BASE_URL = 'http://localhost/learn/restapi';

const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASSWORD = 'toor';
const DB_NAME = 'restapi';

spl_autoload_register(function ($class) {
    $path = str_replace('\\', '/', $class) . '.php';

    if (file_exists($path)) {
        include_once($path);
    }
});