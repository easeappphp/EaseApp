<?php
declare(strict_types=1);

namespace EaseAppPHP\Foundation;

use \Illuminate\Container\Container;

if (interface_exists('\EaseAppPHP\Foundation\Interfaces\BaseCliControllerInterface')) {
	
    class BaseCliController implements \EaseAppPHP\Foundation\Interfaces\BaseCliControllerInterface
    {
		protected $container;
		protected $config;
		protected $matchedRouteDetails;
		protected $argc;
		protected $argv;
		
        public function __construct(Container $container)
		{
			$this->container = $container;
			$this->config = $this->container->get('config');
			$this->matchedRouteDetails = $this->container->get('MatchedRouteDetails');
			$this->argc = $this->container->get('argc');
			$this->argv = $this->container->get('argv');
			
		}
		
		/**
		 * Check if an action exists on the controller.
		 *
		 * @param  string  $method
		 * @return boolean
		 */
		public function checkIfActionExists($method)
		{
			$handler = array($this, $method);
			
			if (is_callable($handler)) { 
        
				return true;
				
            }
			
			return false;
			
		}

        /**
         * Handle calls to missing methods on the controller.
         *
         * @param  string  $method
         * @param  array  $parametersArray
         * @return mixed
         *
         * @throws \BadMethodCallException
         */
        public function __call($method, $parametersArray)
        {
            throw new \BadMethodCallException(sprintf(
                'Method %s::%s does not exist.', static::class, $method
            ));
			
			/* $parametersImploded = implode(', ', $parametersArray);
            print "Call to method $method() with parameters '$parametersImploded' failed!\n";
			 */
        }
		
		/**
		 * Handle calls to missing static methods on the controller.
		 *
		 * @param  string  $method
		 * @param  array  $parametersArray
		 * @return mixed
		 *
		 * @throws \BadMethodCallException
		 */
		public static function __callStatic($method, $parametersArray)
		{
			throw new \BadMethodCallException(sprintf(
                'Method %s::%s does not exist.', static::class, $method
            ));
			
			/* // Note: value of $method is case sensitive.
			echo "Call to static method $method() with parameters "
				 . implode(', ', $parametersArray). "failed!\n"; */
		}
		
		/**
		 * Create View File Name With Path
		 * Note: View directory names should not contain the . character.
		 * @return string
		 */
		public function createViewFileNameWithPath($pageFileName)
		{
			$fileNameParts = preg_replace('/\./', '/', $pageFileName, (substr_count($pageFileName, '.') - 1));
			
			return $fileNameParts;			
		} 
    }
	
}

