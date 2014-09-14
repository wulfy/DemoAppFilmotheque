<?php
/**
* This file is part of the php-circuit-breaker package.
*
* @link https://github.com/ejsmont-artur/php-circuit-breaker
* @link http://artur.ejsmont.org/blog/circuit-breaker
* @author Artur Ejsmont
*
* For the full copyright and license information, please view the LICENSE file.
*/
namespace DemoApp\Utils\CircuitBreaker;
use DemoApp\Utils\CircuitBreaker\CircuitBreaker;
use DemoApp\Utils\Storage\ApcStorage;

/**
* Allows easy assembly of circuit breaker instances.
*
* @see Ejsmont\CircuitBreaker\CircuitBreakerInterface
* @package Ejsmont\CircuitBreaker\PublicApi
*/
class Factory {
	/**
	* Creates a circuit breaker with same settings for all services using raw APC cache key.
	* APC raw adapter is faster than when wrapped with array decorator as APC uses direct memory access.
	*
	* @param int $maxFailures how many times do we allow service to fail before considering it offline
	* @param int $retryTimeout how many seconds should we wait before attempting retry
	*
	* @return CircuitBreakerInterface
	*/
	public static function getApcCircuitBreaker($maxFailures = 20, $retryTimeout = 30) {
		$storage = new ApcStorage();
		return new CircuitBreaker($storage, $maxFailures, $retryTimeout);
	}
	
	
}