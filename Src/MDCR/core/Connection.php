<?php

namespace MDCR\core;

use function PHPSTORM_META\type;

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
            $envFile = parse_ini_file(dirname(__DIR__) . "/../../.env");
            \R::setup("mysql:host=" . $envFile['MYSQL_SERVER'] . ";dbname=" . $envFile['DB_NAME'], $username = $envFile['DB_USER'], $password = $envFile['DB_PASSWORD'], $frozen = false);
        }
    }



    public function createModel(string $table)
    {
        $this->loadDb();
        $model = \R::dispense($table);

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

    public function delete($model)
    {
        $bean = \R::load($model->getTable(), $model->id);
        return \R::trash($bean);
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

        if ($model && $model != 'NULL') {
            return $model;
        } else {
            return null;
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

        if ($model && $model != 'NULL') {
            return $model;
        } else {
            return [];
        }
    }

    public function get($query, $table)
    {
        $rows = \R::getAll($query);
        $models = \R::convertToBeans($table, $rows);
        return $models;
    }

    public function exec(string $query, array $data)
    {
        $model = \R::getAll($query, $data);
        if ($model && $model != 'NULL') {
            return $model;
        } else {
            return [];
        }
    }
}
