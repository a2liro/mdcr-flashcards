<?php
namespace App;
define('MDCR_START', microtime(true));


require_once 'vendor/autoload.php';
require_once(__DIR__ . '/' . 'Src/MDCR/libs/rb.php');


use MDCR\core\Connection;

$conn = new Connection();


echo $argc . "\n";
echo $argv[1] . "\n";



function migrateModel($table, $fields, $conn, $model) {
    $data = [];
    foreach ($fields as $key => $field) {
        if(in_array('string', $field)) {
            $data[$key] = 'string';
        } elseif (in_array('integer', $field)) {
            $data[$key] = intval(1);
        } elseif (in_array('boolean', $field)) {
            $data[$key] = boolval(true);
        } elseif (in_array('datetime', $field)) {
            $data[$key] = date('Y/m/d');
        }
    }
    $model->create($data);
}

if($argv[1] === 'do') {
    if($argv[2] === 'migrate') {
        $files = glob('Src/Models' . '/*.php');

        foreach ($files as $modelPath) {
            $modelPathArray = explode('/', $modelPath);
            $modelName = '\\MDCR\\Models\\' . explode('.', $modelPathArray[sizeof($modelPathArray) - 1])[0];
            $modelInstance = new $modelName();
            $fields = $modelInstance->getFields();
            $table = $modelInstance->getTable();
            migrateModel($table, $fields, $conn, $modelInstance);
        }
    }
}



echo microtime(true) - MDCR_START . "\n";
