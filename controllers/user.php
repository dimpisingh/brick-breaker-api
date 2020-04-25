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
            $existant_user = user::getExistent($user['email'], $user['username']);
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
            $user['password'] = utils::generatePassword($user['password'], self::$password_salt);
            $location = user::getLocation($request->getIP());
            
            $data = array_merge($user, $location);
            $last_insert_id = user::create($data);
            $data['id'] = $last_insert_id;
            $data['Auth-Token'] = auth::authorize($last_insert_id);
            $data['flag'] = utils::getCountryFlagUrl($data['country_code']);
            $response->setStatus(200)->sendJson($data);
        } else {
            if ($request->headers['Auth-Token']) {
                $user = user::getByAuthToken($request->headers['Auth-Token']);
                if ($user) {
                    $user['flag'] = utils::getCountryFlagUrl($user['country_code']);
                    $response->setStatus(200)->sendJson($user);
                } else {
                    $response->sendStatus(401);
                }
            }
        }
    }

    public function actionIp () {
        $request = $this->request;
        $response = $this->response;

        if ($request->isGet) {
            $ip = $request->getIp();
            $ip = $ip == '127.0.0.1' ? '5.44.37.205' : $ip;
            $response->sendJson($ip);
        } else {
            $response->sendStatus(404);
        }
    }

    public function actionSignin () {
        $request = $this->request;
        $response = $this->response;

        if ($request->isPost) {
            $login = $request->body['login'];
            $password = utils::generatePassword($request->body['password'], self::$password_salt);
            $user = user::getByCredentials($login, $password);

            if ($user) {
                $token = auth::authorize($user['id']);
                $user['Auth-Token'] = $token;
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
            if ($request->headers['Auth-Token']) {
                auth::signOut($request->headers['Auth-Token']);
                $response->sendStatus(200);
            } else {
                $response->sendStatus(403);
            }
        } else {
            $response->sendStatus(404);
        }
    }
}