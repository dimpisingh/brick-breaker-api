<?
require_once 'config.php';

class database {
  protected $host;
  protected $db;
  protected $user;
  protected $password;
  protected $dbh;

  function __construct ($host, $db, $user, $password, $char	=	'utf8') {
    $this->host = $host;
    $this->db = $db;
    $this->user = $user;
    $this->password = $password;
    $opt 	= 	[
				        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
				        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
				        \PDO::ATTR_EMULATE_PREPARES   => false,
		    		];

		$dsn 	= 	"mysql:host=$host;dbname=$db;charset=$char";
		$this->dbh 	= 	new \PDO($dsn, $user, $password, $opt);
  }

  public function create ($query, $params = []) {
    $this->dbh->beginTransaction();
    $this->exec($query, $params);
    $lastInsertId = $this->dbh->lastInsertId();
    $this->dbh->commit();
    return $lastInsertId;
  }

  public function exec ($query, $params = []) {
    $sth = $this->dbh->prepare($query);
		return $sth->execute($params);
  }

  public function getAll ($query, $params = []) {
    $sth = $this->dbh->prepare($query);
	  $sth->execute($params);
    $data = $sth->fetchAll();
    return $data;
  }

  public function getOne ($query, $params = []) {
    $sth = $this->dbh->prepare($query);
	  $sth->execute($params);
    $data = $sth->fetchAll();
    return $data[0];
  }
}

$db = new database($host, $db, $user, $password);
