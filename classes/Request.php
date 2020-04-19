<?

class Request {
    public $headers;
    public $body;
    public $isGet;
    public $isPost;
    public $isPut;
    public $isDelete;

    public function __construct () {
        $this->body = json_decode(file_get_contents("php://input"), true);
        $this->headers = getallheaders();

        $this->isGet = $_SERVER['REQUEST_METHOD'] == 'GET';
        $this->isPost = $_SERVER['REQUEST_METHOD'] == 'POST';
        $this->isPut = $_SERVER['REQUEST_METHOD'] == 'PUT';
        $this->isDelete = $_SERVER['REQUEST_METHOD'] == 'DELETE';
    }

    public function getIP () {
        return $_SERVER['REMOTE_ADDR'];
    }
}
