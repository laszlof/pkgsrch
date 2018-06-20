<?php
declare(strict_types = 1);

namespace Pkgsrch;

use \PDO;

class Search {
  /**
   * Our PDO object
   * @var PDO
   */
  public $_db;

  public function __construct(string $file) {
    $this->_db = new \PDO("sqlite:{$this->_getDbFile($file)}");
  }

  /**
   * Get full path to db file
   *
   * @return string
   */
  protected function _getDbFile(string $file) : string {
    return App::getBaseDir() . "/data/{$file}";
  }

  public function findPackage(string $name) : array {
    $stmt = $this->_db->prepare('SELECT * FROM `packages` WHERE `name` LIKE :name');
    $stmt->execute([':name' => "%{$name}%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
