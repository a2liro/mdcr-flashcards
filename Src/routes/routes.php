<?php
namespace App;

use MDCR\core\Router;


$router = new Router();
// $router->get(['path' => '/user/{id}/name/{name}', 'controller' => 'HelloController', 'action' => 'index']);
$router->get(['/', 'HelloController@index']);
$router->post(['/form', 'HelloController@api']);



$router->get(['/baralhos', 'DeckController@index', 'middleware' => ['auth']]);
$router->get(['/baralhos/criar', 'DeckController@create', 'middleware' => ['auth']]);
$router->post(['/baralhos/criar', 'DeckController@store', 'middleware' => ['auth']]);
$router->get(['/baralhos/{id}/visualizar', 'DeckController@show', 'middleware' => ['auth']]);
$router->get(['/baralhos/{id}/editar', 'DeckController@edit', 'middleware' => ['auth']]);
$router->post(['/baralhos/{id}/update', 'DeckController@update', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/jogar/frente', 'DeckController@playFront', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/jogar/verso/card/{cardId}', 'DeckController@playBack', 'middleware' => ['auth']]);



$router->get(['/baralhos/{deckId}/flash-cards', 'FlashCardController@index', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/flash-cards/criar', 'FlashCardController@create', 'middleware' => ['auth']]);
$router->post(['/baralhos/{deckId}/flash-cards/criar', 'FlashCardController@store', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/flash-cards/{cardId}/nota/{note}', 'FlashCardController@note', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/flash-cards/{id}/editar', 'FlashCardController@edit', 'middleware' => ['auth']]);
$router->post(['/baralhos/{deckId}/flash-cards/{id}/update', 'FlashCardController@update', 'middleware' => ['auth']]);









$router->get(['/register', 'AuthController@register']);
$router->post(['/register', 'AuthController@create']);

$router->get(['/login', 'AuthController@login']);
$router->get(['/logout', 'AuthController@logout']);

$router->post(['/login', 'AuthController@authorize']);

$router->get(['user/{id}', 'UserController@show']);






$router->end();

// Authentication routes
// require_once( __DIR__ . '/' . './auth.php');



