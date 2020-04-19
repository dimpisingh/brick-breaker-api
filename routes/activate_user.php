<?
header('Content-type: application/json');
require_once 'models/user.php';
$serial = @$_POST['serial'];
if (!$serial) {
  print json_encode([
    'success'   => false,
    'status'    => 200,
    'errors'    => [
      'serial' => 'Please provide user serial'
    ]
  ]);die;
}

$post_data = $_POST;
// var_dump($post_data);die;
if (md5($post_data['password']) == md5(substr(md5('fcamisha' . $post_data['serial'] . 'mcafsahi'), 4, 10))) {
  $exists  = user::exists($post_data['serial']);
  if (!$exists) {
    print json_encode([
      'success'    => false,
      'status'     => 200,
      'message'    => 'No user was found with provided serial'
    ]);die;
  }
  $updated = user::update([
    'is_active' => 1,
    'updated_at' => date('Y-m-d H:i:s')
  ], $post_data['serial']);

  if ($updated) {
    print json_encode([
      'success'    => true,
      'status'     => 200,
      'message'    => 'Application was activated successfully'
    ]);
  } else {
    print json_encode([
      'success'   => false,
      'status'    => 500,
      'message'   => 'Internal server error'
    ]);
  }
} else {
  print json_encode([
    'success'   => false,
    'status'    => 200,
    'errors'    => [
      'password'  => 'You have provided wrong password'
    ]
  ]);
}
