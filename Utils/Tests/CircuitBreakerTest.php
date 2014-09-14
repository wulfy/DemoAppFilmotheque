<?php

namespace DemoApp\Utils\Tests;

use DemoApp\Utils\CircuitBreaker\Factory;

class CircuitBreakerTest extends \PHPUnit_Framework_TestCase
{
	private $circuitBreaker = null;

    public function setUp()
    {
		$factory = new Factory();
        $this->circuitBreaker = $factory->getApcCircuitBreaker(2,5);
    }
	
	 protected function tearDown() {
		$this->circuitBreaker = null;
		parent::tearDown();
	}
	
	
	//vérifie que notre application est fonctionnelle (affichage index)
    public function testFailuresTreshold()
    {
			
			//when calling the service for the first time, access is granted (status close).
			$this->assertEquals(true,$this->circuitBreaker->isAvailable("test"));
			
			//multiple failures
			$this->circuitBreaker->reportFailure("test");
			$this->circuitBreaker->reportFailure("test");
			
			//status : OPEN
			$this->assertEquals(false,$this->circuitBreaker->isAvailable("test"));
			
			//other services are still available
			$this->assertEquals(true,$this->circuitBreaker->isAvailable("service Two"));
			
			//wait not enought time to retry, status still "OPEN"
			sleep (2);
			$this->assertEquals(false,$this->circuitBreaker->isAvailable("test"));
			
			//retry timeout reached, status is HALF OPEN (service available)
			sleep (4);
			$this->assertEquals(true,$this->circuitBreaker->isAvailable("test"));
			$this->assertEquals("HALF OPEN",$this->circuitBreaker->printStatus("test"));
			
			//if we get a failure status will be OPEN
			$this->circuitBreaker->reportFailure("test");
			$this->assertEquals(false,$this->circuitBreaker->isAvailable("test"));
			$this->assertEquals("OPEN",$this->circuitBreaker->printStatus("test"));
			
			//half open state again, if success, status il 'closed'
			sleep (6);
			$this->assertEquals(true,$this->circuitBreaker->isAvailable("test"));//half open
			$this->assertEquals("HALF OPEN",$this->circuitBreaker->printStatus("test"));
			$this->circuitBreaker->reportSuccess("test");//success
			$this->assertEquals(true,$this->circuitBreaker->isAvailable("test"));
			$this->assertEquals("CLOSE",$this->circuitBreaker->printStatus("test"));
			
	}	

}