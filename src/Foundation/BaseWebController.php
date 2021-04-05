<?php
namespace EaseAppPHP\Foundation;


if (interface_exists('\EaseAppPHP\Foundation\Interfaces\BaseWebControllerInterface')) {
    class BaseWebController implements \EaseAppPHP\Foundation\Interfaces\BaseWebControllerInterface
    {

        protected $container;

        /**
         * The middleware registered on the controller.
         *
         * @var array
         */
        protected $middleware = [];


        /**
         * Get the middleware assigned to the controller.
         *
         * @return array
         */
        public function getMiddleware()
        {
            return $this->middleware;
        }

        /**
         * Execute an action on the controller.
         *
         * @param  string  $method
         * @param  array  $parameters
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function callAction($method, $parametersArray)
        {
            //return call_user_func_array([$this, $method], $parameters);
			$handler = array($this, $method);

            if (is_callable($handler)) { 
                return call_user_func_array($handler, $parametersArray);
            }			
			
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


    }
}

