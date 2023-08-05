<?php

namespace App;

use MDCR\core\Router;


$router = new Router();
$router->post(['/{deckId}/b', 'CardController@storePublic']);

// $router->get(['path' => '/user/{id}/name/{name}', 'controller' => 'HelloController', 'action' => 'index']);
$router->get(['/', 'HelloController@index']);
$router->post(['/test-post', 'HelloController@testPost']);
$router->get(['/test-get', 'HelloController@testGet']);


$router->get(['/home', 'HelloController@home']);


$router->get(['/organizacoes', 'OrganizationController@index', 'middleware' => ['auth']]);
$router->get(['/organizacoes/criar', 'OrganizationController@create', 'middleware' => ['auth']]);
$router->post(['/organizacoes/criar', 'OrganizationController@store', 'middleware' => ['auth']]);
$router->get(['/organizacoes/{id}/visualizar', 'OrganizationController@show', 'middleware' => ['auth']]);

$router->get(['/categorias/{id}/visualizar', 'CategoryController@show', 'middleware' => ['auth']]);
$router->get(['/categorias/{id}/editar', 'CategoryController@show', 'middleware' => ['auth']]);
$router->get(['/categorias/{id}/excluir', 'CategoryController@show', 'middleware' => ['auth']]);
$router->get(['/categorias/{categoryId}/baralhos/criar', 'DeckController@create', 'middleware' => ['auth']]);
$router->post(['/categorias/{categoryId}/baralhos/criar', 'DeckController@store', 'middleware' => ['auth']]);



$router->get(['/organizacoes/{organizationId}/cursos/criar', 'CourseController@create', 'middleware' => ['auth']]);
$router->post(['/organizacoes/{organizationId}/cursos/criar', 'CourseController@store', 'middleware' => ['auth']]);


$router->get(['/cursos', 'CourseController@index', 'middleware' => ['auth']]);
$router->get(['/cursos/{id}/visualizar', 'CourseController@show', 'middleware' => ['auth']]);
$router->get(['/cursos/{courseId}/categorias/criar', 'CategoryController@create', 'middleware' => ['auth']]);
$router->post(['/cursos/{courseId}/categorias/criar', 'CategoryController@store', 'middleware' => ['auth']]);



$router->get(['/baralhos', 'DeckController@index', 'middleware' => ['auth']]);
$router->get(['/cursos/{courseId}/baralhos/criar', 'DeckController@create', 'middleware' => ['auth']]);
$router->post(['/cursos/{courseId}/baralhos/criar', 'DeckController@store', 'middleware' => ['auth']]);
$router->get(['/baralhos/{id}/visualizar', 'DeckController@show', 'middleware' => ['auth']]);
$router->get(['/baralhos/{id}/editar', 'DeckController@edit', 'middleware' => ['auth']]);
$router->post(['/baralhos/{id}/update', 'DeckController@update', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/jogar/frente/audio', 'DeckController@playFrontAudio', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/jogar/frente/card/{cardId}', 'DeckController@playFront', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/jogar/verso/card/{cardId}', 'DeckController@playBack', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/ouvir-todos', 'DeckController@playAllAudios', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/ouvir-todos/gravados', 'DeckController@playAllAudiosRecorded', 'middleware' => ['auth']]);
//reverse
$router->get(['/baralhos/{deckId}/jogar/frente/reverso', 'DeckController@playFrontReverse', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/jogar/verso/card/{cardId}/reverso', 'DeckController@playBackReverse', 'middleware' => ['auth']]);






$router->get(['/baralhos/{deckId}/cards', 'CardController@index', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/cards/criar', 'CardController@create', 'middleware' => ['auth']]);
$router->post(['/baralhos/{deckId}/cards/criar', 'CardController@store', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/cards/{cardId}/nota/{note}', 'CardController@note', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/cards/{cardId}/nota-reversa/{note}', 'CardController@noteReverse', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/cards/{id}/editar', 'CardController@edit', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/cards/{id}/visualizar', 'CardController@show', 'middleware' => ['auth']]);
$router->post(['/baralhos/{deckId}/cards/{id}/update', 'CardController@update', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/cards/{cardId}/excluir', 'CardController@exclude', 'middleware' => ['auth']]);
$router->get(['/baralhos/{deckId}/cards/{cardId}/delete', 'CardController@delete', 'middleware' => ['auth']]);





$router->get(['/convert-audios', 'CardController@convert64ToFile']);




$router->get(['/register', 'AuthController@register']);
$router->post(['/register', 'AuthController@create']);

$router->get(['/login', 'AuthController@login']);
$router->get(['/logout', 'AuthController@logout']);

$router->post(['/login', 'AuthController@authorize']);

$router->get(['user/{id}', 'UserController@show']);





$router->end();

// Authentication routes
// require_once( __DIR__ . '/' . './auth.php');
