<?php
namespace EaseAppPHP\Foundation\Interfaces;

use Closure;
use Illuminate\Contracts\Container\Container;

interface BaseWebControllerInterface
{
    
    
    /**
     * Get the middleware assigned to the controller.
     *
     * @return array
     */
    public function getMiddleware();
    
    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array  $parametersArray
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parametersArray);
	
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