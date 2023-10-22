<?php
/**
 * Start app file
 *
 **/
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type,Authorization");

 session_name('key_gerada_no_dot_env');
 session_start();


require_once(__DIR__ . '/../helpers/helpers.php');
require_once(__DIR__ . '/../../../vendor/autoload.php');
require_once(__DIR__ . '/../../routes/routes.php');


