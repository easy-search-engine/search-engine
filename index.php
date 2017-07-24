<?php
require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;

$app->get('/', function () use ($app) {
    return '<!DOCTYPE html><html><head></head><body>Hello world</body></html>';
});

$app->run();