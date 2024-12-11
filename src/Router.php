<?php
namespace App;
class Router
{
    public $currentRoute;

    public function __construct()
    {
        // Joriy yo'nalishni olish
        $this->currentRoute = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    // Dinamik yo'nalishlarni ishlatish
    public function resolveRoute($route, $callback, $method): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== $method) {
            return;
        }

        // Yo'nalishni {id} kabi o'zgaruvchilarni regexga o'zgartirish
        $pattern = preg_replace(pattern: /** @lang text */ '/\{([a-zA-Z_]+)\}/', replacement: '([^/]+)', subject: $route);
        $pattern = "~^" . $pattern . "$~";

        // Joriy yo'nalish bilan o'zgaruvchilarni moslashtirish
        if (preg_match($pattern, $this->currentRoute, $matches)) {
            array_shift($matches); // To'liq moslashuvni olib tashlash
            $callback(...$matches); // Parametrlarni callback funksiyasiga uzatish
            exit();
        }
    }

    // GET so'rovi
    public function getRoute($route, $callback): void
    {
        $this->resolveRoute($route, $callback, 'GET');
    }

    // POST so'rovi
    public function postRoute($route, $callback): void
    {
//        $this->resolveRoute($route, $callback, 'POST');
    }
    // Router.php
    public function post($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $resourceId =  $this->getResource();
            $route = str_replace('{id}', $resourceId, $route);
            if ($route==$this->currentRoute){
                $callback($resourceId);
                exit();
            }
        }
    }
    public function getResource()
    {
        if (isset(explode("/", $this->currentRoute)[2])) {
            $resourceId =  explode("/", $this->currentRoute)[2];
            return $resourceId;
        }
        return false;
    }

    public function deleteRoute($route, $callback): void
    {
        $this->resolveRoute($route, $callback, 'DELETE');
    }

}