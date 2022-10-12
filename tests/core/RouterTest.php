<?php

use MDCR\core\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testPost()
    {
        $router = new Router();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/test-post';
        $this->assertTrue($router->post(['/test-post', 'HelloController@testPost']));
        $this->assertFalse($router->post(['/test-get', 'HelloController@testPost']));
        $this->assertFalse($router->get(['/test-post', 'HelloController@testPost']));

    }

    public function testGet()
    {
        $router = new Router();
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test-get';
        $this->assertTrue($router->get(['/test-get', 'HelloController@testGet']));
        $this->assertFalse($router->post(['/test-get', 'HelloController@testPost']));
        $this->assertFalse($router->get(['/test-post', 'HelloController@testPost']));
    }
}