<?php
require_once __DIR__ . "/../bootstrap.php";

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\Validator\Constraints as Assert;

$app = require ROOT . "/app.php";

$app->run();
