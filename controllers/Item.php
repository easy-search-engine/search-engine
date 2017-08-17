<?php
namespace App\Controllers;
use \Silex\Application;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\Validator\Constraints as Assert;

class Item
{
  private $response = [
        "success" => true,
        "data" => [],
        "errors" => []
    ];

  /**
  * Validate item
  * TODO: rating should accept only numbers!
  * @param \Symfony\Component\HttpFoundation\Request, \Silex\Application
  * @return void
  * @throws Exception
  */
  protected function validate(Request $request, Application $app) {

    $constraint = new Assert\Collection([
        'src' => [new Assert\NotBlank(), new Assert\Length(['max'=>200]), new Assert\Url()],
        'rating' => [new Assert\NotBlank(), new Assert\Type("numeric")],
        'date' => [new Assert\NotBlank, new Assert\DateTime()],
    ]);

    $errors = $app['validator']->validate($request->request->getIterator(), $constraint);

    if (count($errors) > 0) {
      foreach ($errors as $error) 
        $this->response['errors'][] = [
            "property" => $error->getPropertyPath(),
            "message" => $error->getMessage()
        ];
      throw new \Exception("Validation errors!");
    }
  }

  /**
   * Create item from request
   *
   * TODO: move item fetching to separate method
   * @param \Symfony\Component\HttpFoundation\Request, \Silex\Application
   * @return created Item as assoc array
   * @throws exception
   **/
  public function createFromRequest(Request $request, Application $app) 
  {
    $stmt = $app['db']->prepare("INSERT INTO item VALUES (NULL, :src, :rating, :date);");
    $stmt->bindValue("src", $request->get('src'));
    $stmt->bindValue("rating", $request->get('rating'));
    $stmt->bindValue("date", $request->get('date'));

    if(!$stmt->execute()) {
        $this->response['errors'][] = "Cannot create item!";
        throw new \Exception("Cannot create item!");
    }

    $stmt = $app['db']->executeQuery("SELECT * FROM item WHERE id=?;", [
          $app['db']->lastInsertId()
    ]);

    return $stmt->fetch();
  }

  /**
  * Import results from crawler
  * @param \Symfony\Component\HttpFoundation\Request, \Silex\Application
  */
  public function createFromApi(Request $request, Application $app) {
    try {
      $this->validate($request, $app);
      $item = $this->createFromRequest($request, $app);

      $this->response['data']['message'] = "Success!";
      $this->response['data']['item'] = $item;
    } catch (\Exception $e) {

        /**
        * Request failed
        */
        $this->response['success'] = false;

        /**
        * Make sure that general info is on first place
        */
        $this->response['errors'] = array_merge([[
            "property" => "*",
            "message" => $e->getMessage()
        ]], $this->response['errors']);

    } finally {
        return $app->json($this->response);
    }
  }
}
