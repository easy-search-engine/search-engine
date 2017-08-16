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


$app = new Silex\Application();

// TODO: turn off in producton!
$app['debug'] = true;

$app['config'] = Symfony\Component\Yaml\Yaml::parse(file_get_contents(SETTINGS_PATH));

// SERVICE PROVIDERS
$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => $app['config']['database'],
]);


$app->post('/item', function (Request $request) use ($app) {

    $constraint = new Assert\Collection([
        'src' => [new Assert\NotBlank(), new Assert\Length(['max'=>200]), new Assert\Url()],
        'rating' => [new Assert\NotBlank(), new Assert\GreaterThanOrEqual(0)],
        'date' => [new Assert\NotBlank, new Assert\DateTime()],
    ]);

    $errors = $app['validator']->validate($request->request->getIterator(), $constraint);

    if (count($errors) > 0)
        return new Response($errors, 200);

    $stmt = $app['db']->prepare("INSERT INTO item VALUES (NULL, :src, :rating, :date);");
    $stmt->bindValue("src", $request->get('src'));
    $stmt->bindValue("rating", $request->get('rating'));
    $stmt->bindValue("date", $request->get('date'));
    $result = $stmt->execute();

    if($result)
        return new Response("Poszło", 200);
    else
        return new Response("Nie poszło", 200);
});


$app->run();
