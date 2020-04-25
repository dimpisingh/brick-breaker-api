<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Auth-Token");
header('Content-type: application/json');

require_once './App.php';
require_once './classes/Response.php';
require_once './classes/Request.php';
require_once './models/base.php';
require_once './controllers/base.php';
require_once './helpers/utils.php';

$request = new Request();
$response = new Response();

if (!$request->isPost && !$request->isGet && !$request->isPut && !$request->isDelete)
    $response->sendStatus(200);

$controller = @$_GET['controller'];
$action = @$_GET['action'];
$action_name = utils::str2CamelCase($action, '-', 'action');
$controller_name = utils::str2CamelCase($controller, '-', '', 'Controller');

if(!file_exists('./controllers/' . $controller . '.php')) {
    $response->sendStatus(404);
}
require_once('./controllers/' . $controller . '.php');
if (!method_exists($controller_name, $action_name)) {
    $response->sendStatus(404);
}

$object = new $controller_name();
$object->{$action_name}();
