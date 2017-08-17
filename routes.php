<?php
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

$app->post('/item', function (Request $request, Application $app) {
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
                $response['errors'][] = [
                    "property" => $error->getPropertyPath(),
                    "message" => $error->getMessage()
                ];
            throw new Exception("Validation errors!");
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
        
        /**
        * Request failed
        */
        $response['success'] = false;

        /**
        * Make sure that general info is on first place
        */
        $response['errors'] = array_merge([[
            "property" => "*",
            "message" => $e->getMessage()
        ]], $response['errors']);

    } finally {
        return $app->json($response);
    }
});
