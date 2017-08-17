<?php
require_once __DIR__ . "/../bootstrap.php";

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

$app = require ROOT . "/app.php";

$app->run();
