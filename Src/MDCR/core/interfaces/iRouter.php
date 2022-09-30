<?php

namespace MDCR\core\interfaces;

interface iRouter
{

  public function get(array $route): bool;
  /**
   * Fuction post receive only array
   **/
  public function post(array $route): bool;
}
