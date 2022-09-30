<?php

namespace MDCR\core;

require_once(__DIR__ . '/' . '../libs/rb.php');



class Connection
{
    public function __constructor()
    {
        $this->loadDb();
    }

    protected function loadDb()
    {
        if (!\R::testConnection()) {
            // \R::setup('mysql:host=localhost;dbname=mdcr_expenses', $username = 'u683933492_mdcr', $password = '', $frozen = false);
            \R::setup('mysql:host=127.0.0.1;dbname=mdcr_expenses', $username = 'root', $password = '', $frozen = false);
        }
    }



    public function createModel(string $table)
    {
        $this->loadDb();
        $model =  \R::dispense($table);

        return $model;
    }

    public function store($model)
    {
        \R::store($model);
    }

    public function update($model)
    {
        $bean = \R::load($model->getTable(), $model->id);
        foreach ($model as $key => $value) {
            $bean->{$key} = $value;
        }
        \R::store($bean);
    }

    public function findOneByParams(string $table, array $data)
    {
        $this->loadDb();
        $query = '';
        $paramns = [];
        foreach ($data as $field => $value) {

            if ($query == '') {
                $query = "{$field} = ?";
            } else {
                $query = $query . " and {$field} = ?";
            }
            $paramns[] = $value;
        }
        $model = \R::findOne($table, $query, $paramns);

        if ($model) {
            return $model;
        }
    }

    public function findAllByParams(string $table, array $data)
    {
        $this->loadDb();

        $query = '';
        $paramns = [];
        foreach ($data as $field => $value) {

            if ($query == '') {
                $query = "{$field} = ?";
            } else {
                $query = $query . " and {$field} = ?";
            }
            $paramns[] = $value;
        }
        $model = \R::findAll($table, $query, $paramns);

        if ($model) {

            return $model;
        }
    }
}
