<?
require_once 'models/user.php';
$post_data = $_POST;

$mandatory_keys = ['email'];
foreach ($mandatory_keys as $key) {
  if (empty($post_data[$key])) {
    header('Content-type: application/json');
    print json_encode([
      'success'  => false,
      'message'  => 'Please provide user ' . $key,
      'status'   => 200
    ]);die;
  }
}
$post_data['name'] = null;
if (!$post_data['serial']) {
  $post_data['serial']       = rand(111111, 999999);
  while(user::exists($post_data['serial'])) {
    $post_data['serial']    = rand(111111, 999999);
  }
}
$post_data['hash']         = md5(substr(md5('fcamisha' . $post_data['serial'] . 'mcafsahi'), 4, 10));
$post_data['is_active']    = $post_data['is_active'] == 'true' ? 1 : 0;
$post_data['created_at']   = date('Y-m-d H:i:s');

if(user::save($post_data)) {
  $post_data['is_active'] = (bool)$post_data['is_active'];
  header('Content-type: application/json');
  print json_encode([
    'success'  => true,
    'message'  => 'User successfully created',
    'data'     => $post_data,
    'status'   => 200
  ]);
}
;
