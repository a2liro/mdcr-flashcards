<?php 

namespace App\Controllers;

use MDCR\core\Response;

class Controller
{
  public function __construct(
    private Response $response
  )
  {
  }

  public function view($viewFile, $data = [])
  {
    // $response = new Response();
    $this->response->view($viewFile, $data);
  }

  public function json($data)
  {
    $this->response->json($data);
  }

  public function redirect(string $path) {
    $this->response->redirect($path);
  }
}
