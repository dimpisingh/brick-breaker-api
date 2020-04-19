<?
require_once './models/auth.php';
require_once './models/hi-score.php';

class HiScoreController extends BaseController {
    public function actionIndex () {
        $request = $this->request;
        $response = $this->response;
        $auth_token = $request->headers['authToken'];

        if (!$auth_token) {
            $response->sendStatus(401);
        }
        $authorized_user = auth::get_user_id($auth_token);
        if (!$authorized_user) {
            $response->sendStatus(401);
        }

        if ($request->isPost) {
            $score = $request->body['score'];
            $token = $request->body['verifyToken'];
            if (!utils::is_hi_score_token_valid($token, $score, $auth_token)) {
                $response->sendStatus(400);
            }
            $highest_score = hi_score::get_by_user($authorized_user);
            if ($highest_score['id']) {
                if ($highest_score['score'] < $score) {
                    hi_score::update_record($score, $authorized_user);
                }
            } else {
                hi_score::create_new($score, $authorized_user);
            }

            $response->sendStatus(200);
        }
    }

    public function actionRating () {
        $request = $this->request;
        $response = $this->response;
        $auth_token = $request->headers['authToken'];

        if (!$auth_token || !auth::is_authorized($auth_token)) {
            $response->sendStatus(401);
        }
        if ($request->isGet) {
            $response->sendJson(hi_score::get_rating());
        }
        $response->sendStatus(404);
    }
}