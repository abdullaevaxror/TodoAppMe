<?php

require 'helpers.php';
require 'bootstrap.php';

use App\Controller;
use App\Router;

$router = new Router();
$controller = new Controller();

$router->getRoute('/', [$controller, 'home']);
$router->getRoute('/bot', [$controller, 'bot']);
$router->getRoute('/login', [$controller, 'login']);
$router->getRoute('/sing', [$controller, 'sing']);
$router->getRoute('/todos', [$controller, 'showTodos']);
$router->getRoute('/todos/{id}/delete', [$controller, 'deleteTodo']);
$router->getRoute('/todos/{id}/edit', function ($id) use ($controller) {
    $controller->updateTodoForm($id);
});
$router->postRoute('/todos/{id}/edit', function ($id) use ($controller) {
    $controller->updateTodoData($id);
});
$router->postRoute('/todos', [$controller, 'storeTodo']);