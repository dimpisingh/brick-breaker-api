<?
require_once 'db.php';

class App {
  public static $db;

  public static function run($db) {
    self::$db = $db;
  }

}

App::run($db);
