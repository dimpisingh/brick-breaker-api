<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-type: application/json');

require_once './App.php';
require_once './classes/Response.php';
require_once './classes/Request.php';
require_once './models/base.php';
require_once './controllers/base.php';
require_once './helpers/utils.php';

$request = new Request();
$response = new Response();
$controller = @$_GET['controller'];
$action = @$_GET['action'];
$action_name = utils::convert_to_camel_case($action, '-', 'action');
$controller_name = utils::convert_to_camel_case($controller, '-', '', 'Controller');

if(!file_exists('./controllers/' . $controller . '.php')) {
    $response->sendStatus(404);
}
require_once('./controllers/' . $controller . '.php');
if (!method_exists($controller_name, $action_name)) {
    $response->sendStatus(404);
}

$object = new $controller_name();
$object->{$action_name}();
