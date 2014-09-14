<?php

namespace DemoApp\Utils\CircuitBreaker;
use DemoApp\Utils\CircuitBreaker\CircuitBreakerInterface;
use DemoApp\Utils\Storage\StorageInterface;

class CircuitBreaker implements CircuitBreakerInterface {

	//dummies value, just to have 3 different var
	private $CLOSE_STATUS 		= 1;
	private $OPEN_STATUS 		= 2;
	private $HALF_OPEN_STATUS 	= 3;

	/**
	* @var CircuitBreakerInterface used to load/save availability statistics
	*/
	protected $storageAdapter;
	/**
	* @var int default threshold, if service fails this many times will be disabled
	*/
	protected $defaultMaxFailures;
	/**
	* @var int how many seconds should we wait before retry
	*/
	protected $defaultRetryTimeout;

	 /**
	* Configure instance with storage implementation and default threshold and retry timeout.
	*
	* @param StorageInterface $storage storage implementation
	* @param int $maxFailures default threshold, if service fails this many times will be disabled
	* @param int $retryTimeout how many seconds should we wait before retry
	*/
	public function __construct(StorageInterface $storage, $maxFailures = 20, $retryTimeout = 60) {
		$this->storageAdapter = $storage;
		$this->defaultMaxFailures = $maxFailures;
		$this->defaultRetryTimeout = $retryTimeout;
	}
	
	public function printStatus($serviceName){
	
		$currentStatus = "undefined";
		switch($this->storageAdapter->loadStatus($serviceName, 'status'))
		{
			case 1: $currentStatus = "CLOSE";break;
			case 2: $currentStatus = "OPEN";break;
			case 3: $currentStatus = "HALF OPEN";break;
		}
		
		return $currentStatus;
	
	}
	
	/**
	* Retrieve service status
	*
	*
	*
	*/
	protected function getStatus($serviceName){
		$currentStatus = $this->storageAdapter->loadStatus($serviceName, 'status');
		if($currentStatus == null)
		{
			$this->setStatus($serviceName,$this->CLOSE_STATUS);
			$currentStatus = $this->CLOSE_STATUS;
		}
			
		return $currentStatus;
	}
	
	/**
	* Set service status
	*
	*
	*
	*/
	protected function setStatus($serviceName,$value){
		$this->storageAdapter->saveStatus($serviceName, 'status', $value);
		$this->storageAdapter->saveStatus($serviceName, 'lastTest', time(), true);
	}
	
	/**
	* Retrieve when occured the last status test
	*
	*
	*
	*/
	protected function getLastTestTime($serviceName){
		return (int) $this->storageAdapter->loadStatus($serviceName, 'lastTest');
	}
	
	/**
	* Retrieve number of failures for given service
	*
	*
	*
	*/
	protected function getFailures($serviceName){
		return (int) $this->storageAdapter->loadStatus($serviceName, 'failures');
	}
	
	/**
	* Set number of failures for a given service
	*
	*
	*
	*/
	protected function setFailures($serviceName,$value){
		$this->storageAdapter->saveStatus($serviceName, 'failures', $value);
	}
	
	/**
	* Test if service is available (== circuit breaker is close or half opened)
	*
	*
	*
	*/
	public function isAvailable($serviceName) {
		$status = $this->getStatus($serviceName);

		//if CB is not open (close or half close), access to service is granted
		if($status != $this->OPEN_STATUS)
		{	
			return true;
		}else
		{	
			//we are in a open state
			$lastTestTime = $this->getLastTestTime($serviceName);

			//if we reached reset timeout , status is half opened
			if(($lastTestTime + $this->defaultRetryTimeout) < time())
			{
				$this->setStatus($serviceName,$this->HALF_OPEN_STATUS);
				//set failures to max-1 to allow only one failure
				$this->setFailures($serviceName, $this->defaultMaxFailures - 1);

				return true;
			}else
			{
				//open state and reset timeout not reached, access to service is forbidden
				return false;
			}
		}
		
	}
	
	 /*
	* Report a failure to access/use service
	*/
	public function reportFailure($serviceName) {
		$failures = $this->getFailures($serviceName) + 1;
		$this->setFailures($serviceName, $failures);

		if ($failures >= $this->defaultMaxFailures) {
			$this->setStatus($serviceName,$this->OPEN_STATUS);
		}
	}
	
	/*
	* Report a success to use/access a service
	*/
	public function reportSuccess($serviceName) {
		$status = $this->getStatus($serviceName);
		
		if ($status == $this->HALF_OPEN_STATUS) {
			$this->setFailures($serviceName, 0);
			$this->setStatus($serviceName,$this->CLOSE_STATUS);
		} else {

		}
	}
	
}