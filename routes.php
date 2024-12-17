<?php

use App\Router;

$router = new Router();


if($router->isApiCall()){
    require 'routers/api.php';
}
if($router->isTelegram()){
    require 'routers/telegram.php';
}
require 'routers/web.php';