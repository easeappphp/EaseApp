<?php
declare(strict_types=1);

namespace EaseAppPHP;

use \Illuminate\Container\Container;
use \EaseAppPHP\Core\EAConfig;
use \EaseAppPHP\Core\EAIsConsole;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Server\MiddlewareInterface as Middleware;
use \Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use \Laminas\Diactoros\Response\TextResponse;
use \Laminas\Stratigility\MiddlewarePipe;
use \Psr\Log\LoggerInterface;
use \Laminas\Diactoros\Response\EmptyResponse;
use \EaseAppPHP\Foundation\BaseApplication;

/**
 * App Class
 *
 */
Class App extends BaseApplication
{
	protected $app;
	protected $container;
	protected $middlewareQueue = [];
	protected $eaIsConsoleinstance;
    protected $eaRequestConsoleStatusResult;
	protected $kernelInstance;
    protected $collectedConfigData = [];
	protected $config = [];        
	protected $eaTimerInstance;
	protected $serverRequest;
	protected $initResult;
	protected $eaRouterinstance;
	protected $middlewarePipeQueue;
    protected $middlewarePipeQueueEntries;
	protected $middlewareProcessedResponse;
	protected $serverRequestProcessedResponse;
	protected $serverRequestFinalProcessedResponse;
	protected $emitterStackInstance;
	protected $sapiStreamInstance;
	protected $requestHandlerRunnerServerInstance;
	protected $middlewareProcessor;
	protected $routesList;
	protected $matchedRouteResponse;
	protected $matchedController;
	protected $matchedRouteKey;
	protected $matchedRouteDetails;
	protected $eaConfig;
	protected $argv;
	protected $argc;
	
	/**
	* All of the registered service providers.
	*	
	*/
	protected $serviceProviders = [];
	protected $eaServiceProvidersList;

	/**
	* The names of the loaded service providers.
	*
	* @var array
	*/
	protected $loadedProviders = [];
	protected $eaLoadedServiceProvidersList;	
	protected $response;
		
	
	/**
	 * Accepts .env file path and loads the values into $ENV Superglobals. Also, Creates a Container.
     * Extract Config Values into an array, Create ServerRequest.
	 *
	 * @param string $envFilePath
     * @param object $container
     * @param string $configSource
     * @param array $configSourceValueDataType
     * @param array $configSourceValueData
	 * @return object
	 */
	//public function __construct($envFilePath, $container, $configSource, $configSourceValueDataType, $configSourceValueData)
	public function __construct(Container $container)
    //public function __construct(Container $container)
    {	
		$this->container = $container;
		
		$this->config = $this->container->get('config');   
		
		//Check if the request is based upon Console or Web
		$eaIsConsole = new EAIsConsole();
		$this->container->instance('EAIsConsole', $eaIsConsole);
		$this->eaIsConsoleInstance = $this->container->get('EAIsConsole')->checkSTDIN();
		
		//Save EA REQUEST Console Status Result to Container
		$this->container->instance('EARequestConsoleStatusResult', $this->eaIsConsoleInstance);
		$this->eaRequestConsoleStatusResult = $this->container->get('EARequestConsoleStatusResult');   
		
		if ($this->container->get('EARequestConsoleStatusResult') == "Web") {
			
			//Web
			$this->serverRequest = $this->container->get('\Laminas\Diactoros\ServerRequestFactory');
		
			$this->response = $this->container->get('\EaseAppPHP\Foundation\BaseWebResponse');
			
		} 

		if ($this->container->get('EARequestConsoleStatusResult') == "Console") {
			
			//Console
			$this->argc = trim(filter_var($GLOBALS['argc'], FILTER_SANITIZE_NUMBER_INT));
			
			$this->argv = $GLOBALS['argv'];
			
			for ($i=0; $i < $this->argc; $i++) {
				
				if (is_numeric($GLOBALS['argv'][$i])) {
					
					$this->argv[$i] = trim(filter_var($GLOBALS['argv'][$i], FILTER_SANITIZE_NUMBER_INT));
					
				} else {
					
					$this->argv[$i] = trim(filter_var($GLOBALS['argv'][$i], FILTER_SANITIZE_STRING));
					
				}
				
			}
			
		}
		
    }
	
	/**
	 * Register Service Providers with the container.
	 *
	 * @param array $serviceProvidersArray
	 * @return object
	 */
	public function init()
	{
		if ($this->container->get('EARequestConsoleStatusResult') == "Console") {
			
			//Console
			if ($_ENV['APP_DEBUG'] == "true") {	
				
				$whoopsHandler = $this->container->get('\Whoops\Run');
				$whoopsHandler->pushHandler(new \Whoops\Handler\PlainTextHandler());
				$whoopsHandler->register();
				
			}
			
		} /*else {
		
			//Web
			//Loop through and Register Service Providers First
			foreach ($this->getConfig()["mainconfig"]["providers"] as $serviceProvidersArrayRowKey => $serviceProvidersArrayRowValue) {
				$registeredServiceProviders[$serviceProvidersArrayRowKey] = new $serviceProvidersArrayRowValue($this->container);
				$registeredServiceProviders[$serviceProvidersArrayRowKey]->register();
				
				$this->serviceProviders[] = $serviceProvidersArrayRowValue; // NOT WORKING STILL
				
				//Save available Serviceproviders to Container
				$this->container->instance('EAServiceProviders', $this->serviceProviders);
				$this->eaServiceProvidersList = $this->container->get('EAServiceProviders'); 
			}
			
			foreach ($this->eaServiceProvidersList as $serviceProvidersArrayRowKey => $serviceProvidersArrayRowValue) {
				$registeredServiceProviders[$serviceProvidersArrayRowKey]->boot();
				
				//https://stackoverflow.com/questions/829823/can-you-create-instance-properties-dynamically-in-php
				//https://stackoverflow.com/questions/33486639/create-an-object-inside-for-loop

				$this->loadedProviders[] = $serviceProvidersArrayRowValue; // NOT WORKING STILL
				
				//Save available Serviceproviders to Container
				$this->container->instance('EALoadedServiceProviders', $this->loadedProviders);
				$this->eaLoadedServiceProvidersList = $this->container->get('EALoadedServiceProviders'); 
			}
			
		}*/
		//Loop through and Register Service Providers First
		foreach ($this->getConfig()["mainconfig"]["providers"] as $serviceProvidersArrayRowKey => $serviceProvidersArrayRowValue) {
			$registeredServiceProviders[$serviceProvidersArrayRowKey] = new $serviceProvidersArrayRowValue($this->container);
			$registeredServiceProviders[$serviceProvidersArrayRowKey]->register();
			
			$this->serviceProviders[] = $serviceProvidersArrayRowValue; // NOT WORKING STILL
			
			//Save available Serviceproviders to Container
			$this->container->instance('EAServiceProviders', $this->serviceProviders);
			$this->eaServiceProvidersList = $this->container->get('EAServiceProviders'); 
		}
		
		foreach ($this->eaServiceProvidersList as $serviceProvidersArrayRowKey => $serviceProvidersArrayRowValue) {
			$registeredServiceProviders[$serviceProvidersArrayRowKey]->boot();
			
			//https://stackoverflow.com/questions/829823/can-you-create-instance-properties-dynamically-in-php
			//https://stackoverflow.com/questions/33486639/create-an-object-inside-for-loop

			$this->loadedProviders[] = $serviceProvidersArrayRowValue; // NOT WORKING STILL
			
			//Save available Serviceproviders to Container
			$this->container->instance('EALoadedServiceProviders', $this->loadedProviders);
			$this->eaLoadedServiceProvidersList = $this->container->get('EALoadedServiceProviders'); 
		}
	}
	
	/**
	 * Run Application
	 *
	 * @param array $middlewareQueueArray
	 * @return object
	 */
	//public function run($middlewareQueueArray = array())
	public function run()
	{
        if ($this->container->get('EARequestConsoleStatusResult') == "Console") {
			
			//Console
			/* echo "echo on cli\n";
			echo "timezone: " . $this->getConfig()["mainconfig"]["timezone"] . "\n";
			
			echo "There are $this->argc arguments\n";

			for ($i=0; $i < $this->argc; $i++) {
				echo $this->argv[$i] . "\n";
			}
			
			if ($this->argc == "2") {
				
				if (($this->argv[0] == "console.php") && ($this->argv[1] == "/cron-job/sample")) {
					echo "inside 1th argument\n";
				}
				
			} */
			
		} else {
		
			//Web
			$this->middlewarePipeQueueEntries = $this->container->get('middlewarePipeQueueEntries');
			
			//https://docs.laminas.dev/laminas-httphandlerrunner/emitters/
			//Define Max Buffer Length for Files
			$maxBufferLength = (int) "8192";

			$sapiStreamEmitter = new \Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter($maxBufferLength); 
			$conditionalEmitter = new class ($sapiStreamEmitter) implements \Laminas\HttpHandlerRunner\Emitter\EmitterInterface {

				private $emitter;

				public function __construct(\Laminas\HttpHandlerRunner\Emitter\EmitterInterface $emitter)
				{
						$this->emitter = $emitter;
				}

				public function emit(\Psr\Http\Message\ResponseInterface $response) : bool
				{
						if (! $response->hasHeader('Content-Disposition')
								&& ! $response->hasHeader('Content-Range')
						) {
								return false;
						}
						return $this->emitter->emit($response);
				}
				
			};


			$emitterStack = new \Laminas\HttpHandlerRunner\Emitter\EmitterStack();
			$this->container->instance('\Laminas\HttpHandlerRunner\Emitter\EmitterStack', $emitterStack);

			$this->emitterStackInstance = $this->container->get('\Laminas\HttpHandlerRunner\Emitter\EmitterStack');

			$this->emitterStackInstance->push(new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter());
			$this->emitterStackInstance->push($conditionalEmitter);
			
			$serverRequest = $this->container->get('\Laminas\Diactoros\ServerRequestFactory');
			
			// RUN MIDDLEWARE using HTTPHandlerRunner Laminas Library
			//https://docs.laminas.dev/laminas-stratigility/v3/middleware/#middleware
			$requestHandlerRunnerServer = new \Laminas\HttpHandlerRunner\RequestHandlerRunner(
				$this->middlewarePipeQueueEntries,
				$this->emitterStackInstance,
				static function () use ($serverRequest) {
					return $serverRequest;
				},
				static function (\Throwable $e) {
						$response = (new \Laminas\Diactoros\ResponseFactory())->createResponse(500);
						$response->getBody()->write(sprintf(
								'An error occurred: %s',
								$e->getMessage
						));

						return $response;
				}
			);
			
			$this->container->instance('\Laminas\HttpHandlerRunner\RequestHandlerRunner', $requestHandlerRunnerServer);
			$this->requestHandlerRunnerServerInstance = $this->container->get('\Laminas\HttpHandlerRunner\RequestHandlerRunner');
			$this->requestHandlerRunnerServerInstance->run();
			
		}
	}
        
	/**
	* Get the config array of the application.
	*
	* @return string
	*/
	public function getConfig()
	{
		return $this->config;
	}
	
	/**
	* Get the Routing Engine User Rules/Routes array of the application.
	*
	* @return string
	*/
	public function getRoutes()
	{

	}
}