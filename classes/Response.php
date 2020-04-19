<?

class Response {
    public function setStatus ($code) {
        http_response_code($code);
        return $this;
    }

    public function sendJson ($data) {
        print json_encode($data);
        exit();
    }

    public function sendStatus($code) {
        $this->setStatus($code);
        exit();
    }
}
