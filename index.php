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
$action = 'action' . ucfirst(@$_GET['action']);
$controllers = ['user'];
if (!in_array($controller, $controllers)) {
  print json_encode([
    'success'    => false,
    'status'     => 404,
    'message'    => 'Not found'
  ]);
  die;
}
$controller_name = ucfirst($controller) . 'Controller';
require_once('./controllers/' . $controller . '.php');
$object = new $controller_name();
$object->{$action}();
?>
