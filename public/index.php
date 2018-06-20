<?php

require_once __DIR__ . '/../vendor/autoload.php';

$slim = new Slim\App();
$app = new \Pkgsrch\App($slim);

$app->run();
