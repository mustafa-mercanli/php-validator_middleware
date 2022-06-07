<?php
require './validator.php';
$config = require './config.php';

header('Content-Type: application/json; charset=utf-8');

if ($config->debug){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
else{
    ini_set('display_errors', 0);
    error_reporting(0);
}


$__PARAMS = json_decode(json_encode($_GET)) or 
$__PARAMS = json_decode(json_encode($_POST)) or 
$__PARAMS = json_decode(file_get_contents("php://input")) or
$__PARAMS = [];

if (isset($__SCHEMA)){
    $v = new Validator($__SCHEMA);
    $v->validate($__PARAMS);
    if ($v->errors()){
        http_response_code(400);
        die(json_encode($v->errors()));
    }

}

?>