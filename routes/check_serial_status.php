<?
require_once 'models/user.php';
$serial = $_GET['serial'];
$user_data = user::find_one($serial);

header('Content-type: application/json');
if ($user_data) {
  $response_data['is_blocked'] = (bool)$user_data['is_blocked'];
  $response_data['is_active'] = (bool)$user_data['is_active'];
  print json_encode([
    'success'  => true,
    'data'     => $response_data,
    'status'   => 200
  ]);
} else {
  print json_encode([
    'success'  => true,
    'data'     => [],
    'status'   => 200
  ]);
}
die;
