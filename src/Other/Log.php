<?php
declare(strict_types=1);

namespace EaseAppPHP\Other;

use \Illuminate\Container\Container;

use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;
use \Monolog\Handler\FirePHPHandler;

/**
 * Log Class
 *
 */
 
class Log
{
	protected $container;
	protected $config;
	protected $response;
		
	/**
	 * Write Log to specific channel
	 *
	 */
	public static function channel(Container $container, $message)
	{
            

            
	}
	
	/**
	 * Write Log to on-demand channel stack
	 *
	 */
	public static function stack()
	{
            

            
	}
	
	/**
	 * Write Log to on-demand channel configuration at runtime
	 *
	 */
	public static function build()
	{
            

            
	}
	
	/**
	 * Accepts Extracted Config Array
	 *
	 * @param array $configArray
	 * @return array
	 */
	public static function info(Container $container, $message)
	{
		$this->container = $container;
		
		$this->config = $this->container->get('config');
		
		
		//$this->createMonologObject();
		
		
		return $this->config;
	}
	
	/**
	 * Create Monolog Object
	 *
	 */
	public function createMonologObject()
	{
            

            
	}
	
	
			
}