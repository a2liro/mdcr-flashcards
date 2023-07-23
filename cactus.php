<?php
namespace App;

require_once 'vendor/autoload.php';
require_once(__DIR__ . '/' . 'Src/MDCR/libs/rb.php');



use MDCR\core\Connection;

$conn = new Connection();


define('MDCR_START', microtime(true));

echo $argc . "\n";
echo $argv[1] . "\n";



function migrateModel($table, $fields, $conn, $model) {
//    $model = $conn->createModel($table, $conn);

    foreach ($fields as $key => $field) {
        var_dump($field, $table);
        if(in_array('string', $field)) {
            var_dump(true, $key);
            $model->{$key} = 'string';
        }
    }
    var_dump($model);
    $model->store();
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
            var_dump($modelInstance);
            die();
//            migrateModel($table, $fields, $conn, $modelInstance);
        }
    }
}



echo microtime(true) - MDCR_START . "\n";
