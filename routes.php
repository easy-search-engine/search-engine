<?php

$app->post('/item', function (Request $request) use ($app) {
    $responseCode = 200;
    $response = [
        "success" => true,
        "data" => [],
        "errors" => []
    ];
    
    try {
        $constraint = new Assert\Collection([
            'src' => [new Assert\NotBlank(), new Assert\Length(['max'=>200]), new Assert\Url()],
            'rating' => [new Assert\NotBlank(), new Assert\GreaterThanOrEqual(0)],
            'date' => [new Assert\NotBlank, new Assert\DateTime()],
        ]);

        $errors = $app['validator']->validate($request->request->getIterator(), $constraint);

        if (count($errors) > 0) {
            foreach ($errors as $error) 
                $response['errors'][] = $error;
            throw new Exception("Validation errors has occured!");
        }

        $stmt = $app['db']->prepare("INSERT INTO item VALUES (NULL, :src, :rating, :date);");
        $stmt->bindValue("src", $request->get('src'));
        $stmt->bindValue("rating", $request->get('rating'));
        $stmt->bindValue("date", $request->get('date'));
        $result = $stmt->execute();

        if(!$result) {
            $response['errors'][] = "Cannot create item!";
            throw new Exception("Cannot create item!");
        }
        
        $response['data'][] = ["Success!"];
        
    } catch (Exception $e) {
        $response['success'] = false;
        $response['errors'][] = $e->getMessage();
    } finally {
        return new Response(json_encode($response), $responseCode);
    }
});
