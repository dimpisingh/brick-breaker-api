<?
require_once 'models/user.php';
require_once 'models/auth.php';

class UserController extends BaseController {
    protected static $password_salt = '#7oP&6ma09!';

    public function actionIndex () {
        $request = $this->request;
        $response = $this->response;

        if ($request->isPost) {
            $user = $request->body;
            $existant_user = user::get_existant($user['email'], $user['username']);
            if ($existant_user) {
                $duplicated_fields;
                foreach ($existant_user as $field => $value) {
                    if ($existant_user[$field] == $user[$field]) {
                        $duplicated_fields[] = $field;
                    }
                }
                $response
                    ->setStatus(403)
                    ->sendJson([
                        'user_exists' => true,
                        'error_fields' => $duplicated_fields
                    ]);
            }
            $user['created_at'] = date('Y-m-d H:i:s');
            $user['password'] = utils::generate_password($user['password'], self::$password_salt);
            $location = user::get_location($request->getIP());
            
            $data = array_merge($user, $location);
            $last_insert_id = user::create($data);
            $data['id'] = $last_insert_id;
            $data['authToken'] = auth::authorize($last_insert_id);
            $data['flag'] = utils::get_country_flag_url($data['country_code']);
            $response->setStatus(200)->sendJson($data);
        } else {
            if ($request->headers['authToken']) {
                $user = user::get_by_auth_token($request->headers['authToken']);
                if ($user) {
                    $user['flag'] = utils::get_country_flag_url($user['country_code']);
                    $response->setStatus(200)->sendJson($user);
                } else {
                    $response->sendStatus(401);
                }
            }
        }
    }

    public function actionSignin () {
        $request = $this->request;
        $response = $this->response;

        if ($request->isPost) {
            $login = $request->body['login'];
            $password = utils::generate_password($request->body['password'], self::$password_salt);
            $user = user::get_by_credentials($login, $password);

            if ($user) {
                $token = auth::authorize($user['id']);
                $user['authToken'] = $token;
                $response->setStatus(200)->sendJson($user);
            } else {
                $response->sendStatus(401);
            }
        } else {
            $response->sendStatus(404);
        }
    }

    public function actionSignout () {
        $request = $this->request;
        $response = $this->response;

        if ($request->isPost) {
            if ($request->headers['authToken']) {
                auth::signOut($request->headers['authToken']);
                $response->sendStatus(200);
            } else {
                $response->sendStatus(403);
            }
        } else {
            $response->sendStatus(404);
        }
    }
}