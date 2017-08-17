<?php
namespace App\Tests;


use \Silex\WebTestCase;
use App\Controllers\Item;

class ItemTest extends WebTestCase
{
  public function createApplication()
  {
      $app = require __DIR__.'/../app.php';
      $app['debug'] = true;
      unset($app['exception_handler']);

      return $app;
  }
  /**
   * Test creating item from request via API
   **/
  public function testCreationFromRequest()
  {
      $client = $this->createClient();
      $crawler = $client->request('POST', '/item', []);
      $responseData = json_decode($client->getResponse()->getContent(), true);
      $this->assertEquals(False, $responseData['success']);
      $this->assertEquals(4, count($responseData['errors']));

      $crawler = $client->request('POST', '/item', [
        "src" => "",
        "rating" => "1",
        "date" => "1997-08-19 20:20:20"
      ]);

      $responseData = json_decode($client->getResponse()->getContent(), true);
      $this->assertEquals(False, $responseData['success']);
      $this->assertEquals(2, count($responseData['errors']));

      $crawler = $client->request('POST', '/item', [
        "src" => "http://localhost",
        "rating" => "bardzo dobry rejting",
        "date" => "1997-08-19 20:20:20"
      ]);

      $responseData = json_decode($client->getResponse()->getContent(), true);
      $this->assertEquals(False, $responseData['success']);
      $this->assertEquals(2, count($responseData['errors']));

      
      $crawler = $client->request('POST', '/item', [
        "src" => "http://localhost",
        "rating" => "1",
        "date" => "1997-08-19 20:20"
      ]);

      $responseData = json_decode($client->getResponse()->getContent(), true);
      $this->assertEquals(False, $responseData['success']);
      $this->assertEquals(2, count($responseData['errors']));

      $crawler = $client->request('POST', '/item', [
        "src" => "http://localhost",
        "rating" => "1",
        "date" => "1997-08-19 20:20:20"
      ]);

      $responseData = json_decode($client->getResponse()->getContent(), true);
      $this->assertEquals(true, $responseData['success']);
      $this->assertEquals(0, count($responseData['errors']));

  }
}
