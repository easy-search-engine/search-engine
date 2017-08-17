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

      $this->assertGreaterThan(0, count($responseData['errors']));
  }
}
