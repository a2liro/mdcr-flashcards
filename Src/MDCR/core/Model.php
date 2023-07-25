<?php

namespace MDCR\core;

use Exception;
use MDCR\core\interfaces\iModel;



abstract class Model implements iModel
{
    protected $connection;
    protected $fields = [];

    public function __construct(string $table, array $fields)
    {
        $this->connection = new Connection();
        $this->whereQuery = '';
        $this->columnsToSelect = '';
        $this->orderQuery = '';
        $this->limitQuery = '';
    }

    public function getTable()
    {
        return $this->table;
    }

    public function create(array $data): object
    {
        $model = $this->connection->createModel($this->table);
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

    public function update(array $data = null): object
    {
        $model = null;
        if ($data == null) {
            $this->connection->update($this);
            $model = $this;
        } else {
            foreach ($this->fields as $field => $value) {
                $model = $this->checkIfKeyExistsInArray($field, $data, $this);
                $this->checkIfKeyIsUnique($field, $data);
            }
            $this->connection->update($model);
        }


        return $model;
    }

    public function delete(): bool | null
    {
        return $this->connection->delete($this);
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
                // função para garatir que não existe no banco
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
        foreach ($this->bean as $key => $value) {
            $this->{$key} = $value;
        }
        return $this;
    }
    public function findAllByParams(array $data)
    {
        return $this->connection->findAllByParams($this->table, $data);
    }

    /**
     * @param $paramns = 'column1, column2, ...'
     * @return Model
     */
    public function select(string $paramns = '')
    {
        if (strlen($paramns) == 0) {
            $this->columnsToSelect = "select * ";
        } else {

            $this->columnsToSelect = "select $paramns ";
        }
        return $this;
    }

    /*
    *
    * @where(['column', 'operator', 'value'], ['column', 'operator', 'value'], ...)
    */
    public function where(...$data)
    {
        // foreach ($data as $paramns) {
        foreach ($data as $item) {
            if (strlen($this->whereQuery) < 1) {
                $this->whereQuery = "where $item[0] $item[1] $item[2]";
            } else {
                $this->whereQuery = $this->whereQuery . " and $item[0] $item[1] $item[2]";
            }
        }
        // }
        return $this;
    }

    public function orderBy($data): Model
    {
        foreach ($data as $key => $item) {
            if (strlen($this->orderQuery) < 1) {
                $this->orderQuery = "order by " . $key . " " . $item;
            } else {
                $this->orderQuery = $this->orderQuery . ", order by " . $key . " " . $item;
            }
        }
        return $this;
    }

    public function limit(int $total)
    {
        $this->limitQuery = " limit $total";
        return $this;
    }

    public function get()
    {
        $query = '';
        if (!$this->columnsToSelect) {
            $this->select();
        }
        $query = $this->columnsToSelect . "from $this->table " . $this->whereQuery
        . " $this->orderQuery"
        . $this->limitQuery;
        $result = $this->connection->get($query, $this->table);
        $models = [];
        foreach ($result as $item) {
            $model = clone $this; //$this->connection->createModel($this->table);
            foreach ($item as $key => $column) {
                $model->{$key} = $column;
            }

            $models[] = $model;
        }
        return $models;
    }

    public function getFields() {
        return $this->fields;
    }

}
