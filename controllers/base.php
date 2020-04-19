<?
class BaseController {
    public function __construct () {
        $this->request = new Request();
        $this->response = new Response();
    }
}