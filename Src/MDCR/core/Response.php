<?php
namespace MDCR\core;

use MDCR\core\interfaces\iResponse;

class Response implements iResponse
{
  public function view($view, $data = [])
  {
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../views');
    $twig = new \Twig\Environment($loader);
    $twig->addFunction(new \Twig\TwigFunction('asset', 'asset'));
    $twig->addFunction(new \Twig\TwigFunction('icon', 'icon'));
    echo $twig->render($view, $data);
  }

  public function json(array $data)
  {
    echo json_encode($data);
  }
  public function redirect(string $path) {
    return header("Location: {$path}");
  }
}


