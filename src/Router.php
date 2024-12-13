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

        $pattern = preg_replace(pattern: /** @lang text */ '/\{([a-zA-Z_]+)\}/', replacement: '([^/]+)', subject: $route);
        $pattern = "~^" . $pattern . "$~";

        if (preg_match($pattern, $this->currentRoute, $matches)) {
            array_shift($matches);
            $callback(...$matches);
            exit();
        }
    }

    public function getResource($route)
    {
        $resourceIndex = mb_stripos($route, '{id}');
        if ($resourceIndex) {
            return false;
        }
        $resourceValue = substr($this->currentRoute, $resourceIndex);

        if ($limit = mb_stripos($resourceValue, '/')) {

            return substr($resourceValue, 0, $limit);
        }

        return $resourceValue ?: false;

    }

    // GET so'rovi
    public function getRoute($route, $callback): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            $resourceValue = $this->getResource($route);
            if ($resourceValue) {
                $resourceRoute = str_replace('{id}', $resourceValue, $route);
                if ($resourceRoute == $this->currentRoute){
                    $callback($resourceRoute);
                    exit();
                }
            }
            if ($route == $this->currentRoute){
                $callback();
                exit();
            }
        }
    }

    // POST so'rovi
//    public function postRoute($route, $callback): void
//    {
//        $this->resolveRoute($route, $callback, 'POST');
//    }
    // Router.php
    public function post($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $resourceId =  $this->getResource($route);
            $route = str_replace('{id}', $resourceId, $route);
            if ($route==$this->currentRoute){
                $callback($resourceId);
                exit();
            }
        }
    }

    public function deleteRoute($route, $callback): void
    {
        $this->resolveRoute($route, $callback, 'DELETE');
    }

}