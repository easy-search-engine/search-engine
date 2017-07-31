<?php
require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

$app = new Silex\Application();

$app['debug'] = true;

$app['config'] = Symfony\Component\Yaml\Yaml::parse(file_get_contents(__DIR__ . '/settings.yml'));

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => $app['config']['database'],
]);

$app->post('/item', function (Request $request) use ($app) {
    $data = [
        'src' => $request->get('src'),
        'rating' => $request->get('rating'),
        'date' => $request->get('date'),
    ];

    $constraint = new Assert\Collection([
        'src' => [new Assert\NotBlank(), new Assert\Length(['max'=>200]), new Assert\Url()],
        'rating' => [new Assert\NotBlank(), new Assert\GreaterThanOrEqual(0)],
        'date' => [new Assert\NotBlank, new Assert\DateTime()],
    ]);

    $errors = $app['validator']->validate($data, $constraint);

    if (count($errors) > 0) {
        $errorsMsg = '';
        foreach ($errors as $error) {
            $errorsMsg .= $error->getPropertyPath().' '.$error->getMessage()."\n";
        }
        return new Response($errorsMsg, 200);
    }

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

$app->get('/', function () use ($app) {
    return '<!DOCTYPE html><html><head></head><body>Hello world</body></html>';
});


$app->run();
