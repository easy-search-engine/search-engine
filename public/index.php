<?php
require_once __DIR__.'/../vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;


/** @var constant project root */
define("ROOT", __DIR__ . '/..');

/** @var constant public folder */
define("PUBLIC_PATH", ROOT . '/public');

/** @var constant path to settings.yml */
define("SETTINGS_PATH", ROOT . '/settings.yml');


$app = require ROOT . "/app.php";


$app->run();
