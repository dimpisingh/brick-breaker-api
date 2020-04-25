<?
require_once './models/auth.php';
require_once './models/hi-score.php';

class HiScoreController extends BaseController {
    public function actionIndex () {
        $request = $this->request;
        $response = $this->response;
        $auth_token = $request->headers['Auth-Token'];

        if (!$auth_token) {
            $response->sendStatus(401);
        }
        $authorized_user = auth::getUserId($auth_token);
        if (!$authorized_user) {
            $response->sendStatus(401);
        }
        $highest_score = hi_score::getByUser($authorized_user);

        if ($request->isPost) {
            $score = $request->body['score'];
            $token = $request->body['verificationToken'];
            if (!utils::isHiScoreTokenValid($token, $score, $auth_token)) {
                $response->sendStatus(400);
            }
            if ($highest_score['id']) {
                if ($highest_score['score'] < $score) {
                    hi_score::updateRecord($score, $authorized_user);
                }
            } else {
                hi_score::createNew($score, $authorized_user);
            }

            $response->sendStatus(200);
        } else {
            $response->sendJson($highest_score['score']);
        }
    }

    public function actionRating () {
        $request = $this->request;
        $response = $this->response;
        $auth_token = $request->headers['Auth-Token'];

        if (!$auth_token || !auth::isAuthorized($auth_token)) {
            $response->sendStatus(401);
        }
        if ($request->isGet) {
            $response->sendJson(hi_score::getRating());
        }
        $response->sendStatus(404);
    }
}