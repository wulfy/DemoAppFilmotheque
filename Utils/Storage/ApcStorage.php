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
namespace DemoApp\Utils\Storage;

use DemoApp\Utils\Storage\StorageInterface;
/**
* Recommended adapter using APC local shared memory cache.
* Super fast, safe, always available (if installed).
* Does not introduce remote point of failure.
* Can be efficently used to load/save each attribute separately if you wish
*
* @see \CircuitBreaker\Storage\StorageInterface
* 
*/
class ApcStorage implements StorageInterface {
	
	/**
	* @var int value in seconds, how long should the stats array persist in cache
	*/
	protected $ttl;
	/**
	* @var string cache key prefix, might be overridden in constructor in the future
	*/
	protected $cachePrefix = "DemoAppCircuitbreaker";
	
	/**
	* Configure instance
	*
	* @param Integer $ttl How long should circuit breaker data persist (between updates)
	* @param String $cachePrefix Value has to be string. If empty default cache key prefix is used.
	*/
	public function __construct($ttl = 3600, $cachePrefix = false) {
		$this->ttl = $ttl;
		
		if ($cachePrefix && is_string($cachePrefix)) {
			$this->cachePrefix = $cachePrefix;
		}
	}
	/**
	* Helper method to make sure that APC extension is loaded
	*
	* @throws \Exception if APC is not loaded
	* @return void
	*/
	protected function checkExtension() {
		if (!function_exists("apc_store")) {
			throw new \Exception("APC extension not loaded.");
		}
	}
	
	/**
	* Loads circuit breaker service status value.
	* For example failures count or last retry time.
	* Method does not care what are the attribute names. They are not inspected.
	* Any string can be passed as service name and attribute name.
	*
	* @param string $serviceName name of service to load stats for
	* @param string $attributeName name of attribute to load
	* @return string value stored or '' if value was not found
	*
	* @throws Exception if storage error occurs, handler can not be used
	*/
	public function loadStatus($serviceName, $attributeName) {
		// make sure extension is loaded
		$this->checkExtension();
		// try to load the data
		$stats = $this->load($this->cachePrefix . $serviceName . $attributeName);
		// if the value loaded is empty return empty string
		if (empty($stats)) {
			$stats = "";
		}
		return $stats;
	}

	
	/**
	* Saves circuit breaker service status value.
	* Method does not care what are the attribute names. They are not inspected.
	* Any string can be passed as service name and attribute name, value can be int/string.
	*
	* Saving in storage is not guaranteed unless flush is set to true.
	* Use calls without flush if you know you will update more than one value and you want to
	* improve performance of the calls.
	*
	* @param string $serviceName name of service to load stats for
	* @param string $attributeName name of the attribute to load
	* @param string $value string value loaded or '' if nothing found
	* @param boolean $flush set to true will force immediate save, false does not guaranteed saving at all.
	* @return void
	*
	* @throws Exception if storage error occurs, handler can not be used
	*/
	public function saveStatus($serviceName, $attributeName, $value, $flush = false) {
		// make sure extension is loaded
		$this->checkExtension();
		// store stats
		$this->save($this->cachePrefix . $serviceName . $attributeName, $value, $this->ttl);
	}

	/**
	* Loads item by cache key.
	*
	* @param string $key
	* @return mixed
	*
	* @throws \Exception if storage error occurs, handler can not be used
	*/
	protected function load($key) {
		return apc_fetch($key);
	}
	/**
	* Save item in the cache.
	*
	* @param string $key
	* @param string $value
	* @param int $ttl
	* @return void
	*
	* @throws \Exception if storage error occurs, handler can not be used
	*/
	protected function save($key, $value, $ttl) {
		$result = apc_store($key, $value, $ttl);

		if ($result === false) {
			throw new \Exception("Failed to save apc key: $key");
		}
	}
}