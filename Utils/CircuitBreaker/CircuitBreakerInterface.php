<?php

namespace DemoApp\Utils\CircuitBreaker;

interface CircuitBreakerInterface {
	/**
	* Check if service is available (according to CB knowledge)
	*
	* @param string $serviceName - arbitrary service name
	* @return boolean true if service is available, false if service is down
	*/
	public function isAvailable($serviceName);

	/**
	* Use this method to let CB know that you failed to connect to the
	* service of particular name.
	*
	* Allows CB to update its stats accordingly for future HTTP requests.
	*
	* @param string $serviceName - arbitrary service name
	* @return void
	*/
	public function reportFailure($serviceName);

	/**
	* Use this method to let CB know that you successfully connected to the
	* service of particular name.
	*
	* Allows CB to update its stats accordingly for future HTTP requests.
	*
	* @param string $serviceName - arbitrary service name
	* @return void
	*/
	public function reportSuccess($serviceName);
}