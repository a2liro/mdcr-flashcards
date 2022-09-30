<?php

namespace MDCR\core\interfaces;

interface iResponse
{
  public function view($path, $data);
  public function json(array $data);
  public function redirect(string $path);
}
