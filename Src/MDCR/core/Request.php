<?php

namespace MDCR\core;

use MDCR\core\interfaces\iRequest;

class Request implements iRequest
{
  public function all(): array | null
  {
    if($_POST) {
      $data = $_POST;
    } else {
      $data = json_decode(file_get_contents('php://input'), true);
    }
    if($data) {
      return $data;
    }
    return null;
  }
}
