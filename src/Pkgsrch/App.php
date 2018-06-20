<?php

declare(strict_types = 1);

namespace Pkgsrch;

use \Slim\App as SlimApp;
use \Exception;

class App {

  /**
   * Slim Application
   * @var SlimApp
   */
  protected $_slim;

  /**
   * Database instance
   * @var
   */
  protected $_db;

  /**
   * Our config array
   * @var array
   */
  protected static $_config = [];

  /**
   * Construct app
   *
   * @param SlimApp $slim
   */
  public function __construct(SlimApp $slim) {
    $this->_slim = $slim;

    $this->_setupRoutes();
  }

  /**
   * Run the app
   */
  public function run() {
    $this->_slim->run();
  }

  /**
   * Get config data
   *
   * @return array
   */
  public static function config() : array {
    if (empty (self::$_config)) {
      $cfg = self::getConfigFilePath();
      if (! file_exists($cfg)) {
        throw new Exception('Config file not found');
      }

      self::$_config = json_decode(file_get_contents($cfg), true);
    }

    return self::$_config;
  }

  /**
   * Get project base directory
   *
   * @return string
   */
  public static function getBaseDir() : string {
    return realpath(__DIR__ . '/../../');
  }

  /**
   * Get config file path
   *
   * @return string
   */
  public static function getConfigFilePath() : string {
    return self::getBaseDir() . '/config/config.json';
  }

  /**
   * Setup routes for all repos
   */
  private function _setupRoutes() {
    foreach ($this->config()['repos'] as $distro => $versions) {
      $distro = strtolower($distro);
      foreach ($versions as $version => $repos) {
        $version = filter_var($version, FILTER_SANITIZE_NUMBER_INT);
        foreach ($repos as $repo => $file) {
          $repo = strtolower($repo);
          $path = "/{$distro}/{$version}/{$repo}/{name}";
          $search = new Search($file);
          $this->_slim->get($path, function ($request, $response, $args) use ($search) {
            $results = $search->findPackage($args['name']);
            return $response->withJson($results);
          });
        }
      }
    }
  }
}
