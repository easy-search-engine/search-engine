<?php
class RoutingTest extends PHPUnit_Extensions_Selenium2TestCase
{

    public function setUp()
    {
        $this->setHost('localhost');
        $this->setPort(4444);
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://localhost/search-engine/');
    }

    public function testHelloWorld()
    {
        $this->url("http://localhost/search-engine/");
        $content = $this->byXPath("//body")->text();
        $this->assertEquals("Hello world", $content);
    }

    public function tearDown()
    {
        $this->stop();
    }
}
