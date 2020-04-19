<?
require_once 'models/user.php';

$data = user::get_list();
echo json_encode([
  'success' => true,
  'status'  => 200,
  'data'    => $data
]);
