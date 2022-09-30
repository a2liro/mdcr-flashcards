<?php

namespace MDCR\core;

use Exception;
use MDCR\core\interfaces\iModel;



class Model implements iModel
{
    protected $connection;

    public function __construct(string $table, array $fields)
    {
        $this->connection = new Connection();
    }

    public function getTable() {
        return $this->table;
    }

    public function create(array $data): object
    {
        $model = $this->connection->createModel($this->table);
        // $model->dispense('teste');
        foreach ($this->fields as $field => $value) {
            $model = $this->checkIfKeyExistsInArray($field, $data, $model);
            $this->checkIfKeyIsUnique($field, $data);
        }

        $this->connection->store($model);

        return $model;
    }

    public function store()
    {
        $this->connection->store($this->bean);
    }

    public function update(array $data): object
    {
        foreach ($this->fields as $field => $value) {
            $model = $this->checkIfKeyExistsInArray($field, $data, $this);
            $this->checkIfKeyIsUnique($field, $data);
        }
        $this->connection->update($model);

        return $model;
        
    }

    public function delete(object $model): bool
    {
        //
    }

    public function show(int $id): object
    {
    }

    protected function checkIfKeyExistsInArray($field, $data, $model)
    {
        if (array_key_exists($field, $data)) {
            $model->{$field} = $data[$field];
        }
        return $model;
    }

    protected function checkIfKeyIsUnique(string $field, array $data)
    {
        $modelInDb = null;
        foreach ($this->fields[$field] as $value) {
            if ($value == 'unique') {
                // função para garatir que não exite no banco
                $modelInDb = $this->connection->findOneByParams($this->table, [$field => $data[$field]]);
                if ($modelInDb) {
                    echo '<br>________________________________________________<br>';
                    echo new Exception("model {$this->table} already exists with {$field} = {$data[$field]}");
                    echo '<br>-------------------------------------------------<br>';
                    die();
                }
            }
            return false;
        }

        // por que isso não funiona
        if (array_key_exists("unique", $this->fields[$field])) {
        }
        return $modelInDb;
    }
    public function findOneByParams(array $data)
    {
        $this->bean = $this->connection->findOneByParams($this->table, $data);
        foreach($this->bean as $key => $value) {
            $this->{$key} = $value;
        }
        return $this;
    }
    public function findAllByParams(array $data)
    {
        return $this->connection->findAllByParams($this->table, $data);
    }
}
