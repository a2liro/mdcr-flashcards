<?php

namespace MDCR\core\interfaces;

interface iModel
{
  public function create(array $data): object;
  public function update(array $data): object;
  public function delete(): bool | null;
  public function show(int $id): object;
}
