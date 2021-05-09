<?php
namespace EaseAppPHP\Foundation;

use Illuminate\Container\Container;

if (interface_exists('\EaseAppPHP\Foundation\Interfaces\BaseWebControllerInterface')) {
    class BaseWebController implements \EaseAppPHP\Foundation\Interfaces\BaseWebControllerInterface
    {

        protected $container;
		protected $config;
		protected $matchedRouteDetails;
		protected $serverRequest;
		protected $queryParams;

        /* public function __construct(Container $container, $config, $matchedRouteDetails, $queryParams)
		{
			
			$this->container = $container;
			$this->config = $config;
			$this->matchedRouteDetails = $matchedRouteDetails;
			$this->queryParams = $queryParams;
			
		} */
		
		public function __construct(Container $container)
		{
			
			$this->container = $container;
			
			$this->config = $this->container->get('config');
			$this->matchedRouteDetails = $this->container->get('MatchedRouteDetails');
			$this->serverRequest = $this->container->get('\Laminas\Diactoros\ServerRequestFactory');
			$this->queryParams = $this->serverRequest->getQueryParams();
			
		}
		
		/**
         * The middleware registered on the controller.
         *
         * @var array
         */
        //protected $middleware = [];


        /**
         * Get the middleware assigned to the controller.
         *
         * @return array
         */
        /*public function getMiddleware()
        {
            return $this->middleware;
        }*/

        /**
         * Execute an action on the controller.
         *
         * @param  string  $method
         * @param  array  $parameters
         * @return \Symfony\Component\HttpFoundation\Response
         */
        /* public function callAction($method, $parametersArray)
        {
            //return call_user_func_array([$this, $method], $parameters);
			$handler = array($this, $method);
			echo "\ncallAction:\n";
			var_dump($parametersArray);
			$parametersArrayValues = array_values($parametersArray);
			
            if (is_callable($handler)) { 
                return call_user_func_array($handler, $parametersArrayValues);
            }			
			
        } */
		
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
			
			$fileNameParts = str_replace(".", "/", $pageFileName);
			
			return $fileNameParts;			
			
		}
    }
}

