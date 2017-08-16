<?php

$app = new App\SearchEngine();


// TODO: turn off in producton!
$app['debug'] = true;

$app['config'] = Symfony\Component\Yaml\Yaml::parse(file_get_contents(SETTINGS_PATH));

// SERVICE PROVIDERS
$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => $app['config']['database'],
]);

require_once __DIR__ . 'routes.php';

return $app;
