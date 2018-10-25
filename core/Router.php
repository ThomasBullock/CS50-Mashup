<?php 

class Router
{
    public $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
    ];

    public static function load($file)
    {
        $router = new static;
        require $file;

        return $router; // cant return $this because this is a static method
    }    

    public function get($uri, $controller)  
    {
        $this->routes['GET'][$uri] = $controller;  // with [$uri] we are adding a new key to the array (instead of the default 0, 1, 2 etc)
    }

    public function post($uri, $controller) 
    {
        $this->routes['POST'][$uri] = $controller;  // with [$uri] we are adding a new key to the array (instead of the default 0, 1, 2 etc)
    }    

    public function direct($uri, $requestType) 
    {
        if (array_key_exists($uri, $this->routes[$requestType])) {
            return $this->routes[$requestType][$uri];
        }

        throw new Exception('No route defined for this URI');
    }
}