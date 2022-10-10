<?php

namespace App\Controllers;

use MDCR\core\Request;
use MDCR\Models\User;

class HelloController extends Controller
{
  // public function index($id, $name)
  public function index()
  {
    $user = (new User())->getCurrentUser();
    $helloWorld = "Hello World! Mandacaru!";
    $this->view('index.twig', ['helloWorld' => $helloWorld, 'user' => $user]);
    // return $this->redirect('/casamento/josilene-e-andre');
  }

  public function hello() {
    echo "hello";
  }

  public function api()
  {
    $arr = array();
    $arr['name'] = 'André';
    $arr['age'] = 21;
    // echo json_encode($arr);
    $this->json($arr);
  }

  public function home(Request $request)
  {
    return $this->redirect('/baralhos');
  }
}
