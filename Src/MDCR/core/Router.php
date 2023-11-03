<?php

namespace MDCR\core;

use App\Controllers\AuthController;
use Error;
use MDCR\core\Response;
use MDCR\core\interfaces\iRouter;

class Router implements iRouter
{
    private bool $routeExists;
    private array $pathsGet;
    private array $pathsPost;
    private array $pathsPut;
    private array $pathsDelete;

    public function __construct()
    {
        $this->routeExists = false;
        $this->pathsGet = array();
        $this->pathsPost = array();
        $this->pathsPut = array();
        $this->pathsDelete = array();
    }

    public function post(array $route): bool
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $this->setPathsPost($route[0]);
        if ($httpMethod == 'POST') {
            return $this->setRoute($route);
        }
        return false;
    }


    public function put(array $route): bool
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $this->setPathsPut($route[0]);
        if ($httpMethod == 'PUT') {
            return $this->setRoute($route);
        }
        return false;
    }

    public function get(array $route): bool
    {
        
        $this->setPathsGet($route[0]);
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
            return $this->setRoute($route);
        }
        return false;
    }


    public function delete(array $route): bool
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $this->setPathsDelete($route[0]);
        if ($httpMethod == 'DELETE') {
            return $this->setRoute($route);
        }
        return false;
    }

    private function setRoute(array $route): bool
    {

        $uriFull = $_SERVER['REQUEST_URI'];
        $uri = explode('?', $uriFull)[0];

        [$controllerName, $action] = explode('@', $route[1]);
        if(strlen($uri) > 1 && $uri[strlen($uri) -1] == "/") {
            $uri = substr($uri, 0, -1);
        }
        $pathArray = explode('/', $route[0]);
        $uriArray = explode('/', $uri);
        $paramns = array();
        $uriBit = array();
        foreach($pathArray as $key => $bitPath){
            if(isset($bitPath[0]) && $key > 0 && $bitPath[0] === '{'){
                $param = preg_replace("/[^0-9a-zA-Z]/", "", $bitPath);
                $paramns[$param] = $uriArray[$key]; 
            } else if($bitPath == $uriArray[$key]){
                $uriBit[] = $bitPath;
            } else {
                return false;
            }
        }
        if(sizeof($pathArray) == sizeof($uriArray)) {

            if(isset($route['middleware'])) {
                $autenticad = $this->checkMiddlewares($route['middleware']);
                if (!$autenticad) {
                    $response = new Response();
                    $_SESSION['nextUrl'] = $_SERVER['REQUEST_URI'];
                    header('Location: /login');
                    $response->json(['User not autorized!']);    
                    //die();
                }
            }

            $response = new Response();
            $this->setRouteExists(true);
            $class = "\\App\\Controllers\\" . $controllerName;
            $controller = new $class($response);
            // $controller->{$route['action']}($_POST, ...$paramns);
            // $request = $_SERVER;
            // $request['data'] = json_decode(file_get_contents('php://input'), true);
            // $request['post'] = $_POST;
            $request = new Request();
            try {
                $controller->{$action}($request, ...$paramns);
            } catch (Error $error) {
                echo $error;
                return false;
                die();
            }
            return true;
        }
        return false;

    }

    private function setRouteExists($exists){
        $this->routeExists = $exists;
    }

    public function end() {
        if(!$this->routeExists) {
            $response = new Response();
            $response->view('templates/404.twig');
        }
    }

    private function setPathsGet(string $path): bool
    {
        if(in_array($path, $this->pathsGet, $strict=true)) {

            $this->messageRouteExists($path, 'GET');
            exit();
            return false;
        }
        $this->pathsGet[] = $path;
        return true;
    }

    private function setPathsDelete($path)
    {
        if(in_array($path, $this->pathsDelete)) {
            $this->messageRouteExists($path, 'DELETE');
            exit();
            return false;
        }
        $this->pathsDelete[] = $path;
    }

    private function setPathsPost($path)
    {
        if(in_array($path, $this->pathsPost)) {
            $this->messageRouteExists($path, 'POST');
            exit();
            return false;
        }
        $this->pathsPost[] = $path;
    }

    private function setPathsPut($path)
    {
        if(in_array($path, $this->pathsPut)) {
            $this->messageRouteExists($path, 'PUT');
            exit();
            return false;
        }
        $this->pathsPut[] = $path;
    }

    private function messageRouteExists(?string $path, ?string $method = '')
    {
        echo "Rota {$method} {$path} já existe";
        $trace = debug_backtrace();
        echo '<br>';
        echo $trace[2]['file'] . ':' . $trace[2]['line'];
        echo '<br>';
        header("HTTP/1.1 500 Server Error duplicated route");
    }

    private function checkMiddlewares ($middlewares): bool
    {
        $this->middlewares = [
            'auth' => AuthController::class,
            'auth_api' => AuthController::class
        ];
        foreach ($middlewares as $middleware) {
            if (array_key_exists($middleware, $this->middlewares)) {
                $middlewarePass = $this->middlewares[$middleware]::{$middleware}();
                if (!$middlewarePass) {
                    return false;
                }
            }
        }
        return true;
    }

}

