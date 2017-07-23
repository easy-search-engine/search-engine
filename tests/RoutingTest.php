<?php
class RoutingTest extends PHPUnit_Extensions_Selenium2TestCase
{

    public function setUp()
    {
        $this->setBrowserUrl('http://localhost/search-engine');
        $this->setBrowser('firefox');
    }

    public function testHelloWorld()
    {
        $content = $this->byXPath("//body")->text();
        $this->assertEquals("Hello world", $content);
    }

    public function tearDown()
    {
        $this->stop();
    }
}