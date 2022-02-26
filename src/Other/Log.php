<?php
declare(strict_types=1);

namespace EaseAppPHP\Other;

use \Illuminate\Container\Container;

use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;
use \Monolog\Handler\FirePHPHandler;

/**
 * Log Class
 * Sample Usage: 
 * Log::channel($container, 'single')->info('Something happened!');
 */
 
class Log
{
	protected static $container;
	protected static $config;
	protected static $logger;
	protected $response;
		
	/**
	 * Write Log to specific channel
	 *
	 */
	public static function channel(Container $container, $channel)
	{
        self::$container = $container;
		self::$config = self::$container->get('config');
		
		foreach (self::$config as $key => $value)
		{
			if ($key == "logging") {
				
				foreach ($value as $key1 => $value1)
				{
					if ($key1 == "channels") {
						
						foreach ($value1 as $key2 => $value2)
						{
							
							if ($key2 == $channel) {
								
								// Create some handlers
								$stream = new StreamHandler($value2["path"], Logger::DEBUG);
								$firephp = new FirePHPHandler();

								// Create the main logger of the app
								self::$logger = new Logger($channel);
								self::$logger->pushHandler($stream);
								self::$logger->pushHandler($firephp);
								
							}
						}
						
					}
				}
				
			}
		} 
		
		return new self;	
            
	}
	
	/**
	 * Write Log to on-demand channel stack
	 *
	 */
	/* public static function stack()
	{
            
		self::$container = $container;
		self::$config = self::$container->get('config');
		
		foreach (self::$config as $key => $value)
		{
			if ($key == "logging") {
				
				foreach ($value as $key1 => $value1)
				{
					if ($key1 == "channels") {
						
						foreach ($value1 as $key2 => $value2)
						{
							if (($key2 == "stack") && ($key2 == $channel)) {
								
								foreach ($value2 as $key3 => $value3)
								{
									if ($key3 == "channels") {
										
										var_dump($value3);
										
									}
								}
								
							}
						}
						
					}
				}
				
			}
		} 
		
		return new self;
            
	} */
	
	/**
	 * Write Log to on-demand channel configuration at runtime
	 *
	 */
	/* public static function build()
	{
            

            
	} */
	
	/**
	 * Writes message to logging destination
	 *
	 */
	public static function debug($message)
	{
		self::$logger->debug($message);
	}
	
	/**
	 * Writes message to logging destination
	 *
	 */
	public static function info($message)
	{
		self::$logger->info($message);
	}
	
	/**
	 * Writes message to logging destination
	 *
	 */
	public static function notice($message)
	{
		self::$logger->notice($message);
	}
	
	/**
	 * Writes message to logging destination
	 *
	 */
	public static function warning($message)
	{
		self::$logger->warning($message);
	}
	
	/**
	 * Writes message to logging destination
	 *
	 */
	public static function error($message)
	{
		self::$logger->error($message);
	}
	
	/**
	 * Writes message to logging destination
	 *
	 */
	public static function critical($message)
	{
		self::$logger->critical($message);
	}
	
	/**
	 * Writes message to logging destination
	 *
	 */
	public static function alert($message)
	{
		self::$logger->alert($message);
	}
	
	/**
	 * Writes message to logging destination
	 *
	 */
	public static function emergency($message)
	{
		self::$logger->emergency($message);
	}
			
}