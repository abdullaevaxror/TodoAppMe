<?php

use App\Router;

$router = new Router();

if($router->isApiCall()){
    require 'routers/api.php';
    exit();
}elseif ($router->isTelegram()){
    require 'routers/telegram.php';
    exit();
}
require 'routers/web.php';