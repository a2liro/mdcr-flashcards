<?php

namespace App;

use MDCR\core\Router;

$router = new Router();

//$router->get(['path' => '/login', 'controller' => 'AuthController', 'action' => 'login']);

$router->get(['/register', 'AuthController@register']);
$router->post(['/register', 'AuthController@create']);

$router->get(['/login', 'AuthController@login']);

$router->post(['/login', 'AuthController@authorize']);

$router->get(['user/{id}', 'UserController@show']);


$router->end();