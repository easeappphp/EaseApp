<?php
namespace EaseAppPHP\Foundation\Interfaces;

use Closure;
use Illuminate\Contracts\Container\Container;

interface BaseWebControllerInterface
{
    
    
    /**
     * Check if an action exists on the controller.
     *
     * @param  string  $method
     * @return boolean
     */
    public function checkIfActionExists($method);
	
    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  string  $method
     * @param  array  $parametersArray
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parametersArray);
	
	/**
     * Handle calls to missing static methods on the controller.
     *
     * @param  string  $method
     * @param  array  $parametersArray
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public static function __callStatic($method, $parametersArray);
	
}