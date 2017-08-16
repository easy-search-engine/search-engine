<?php

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
