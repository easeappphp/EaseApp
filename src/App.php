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
	 * @param object $container
     * @return object
	 */
	public function __construct(Container $container)
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
			
			$this->container->instance('argc', $this->argc);
			
			$this->container->instance('argv', $this->argv);
			
		}
		
    }
	
	/**
	 * Register Service Providers with the container.
	 *
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
			
		}
		
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
			
			$this->loadedProviders[] = $serviceProvidersArrayRowValue; // NOT WORKING STILL
			
			//Save available Serviceproviders to Container
			$this->container->instance('EALoadedServiceProviders', $this->loadedProviders);
			$this->eaLoadedServiceProvidersList = $this->container->get('EALoadedServiceProviders'); 
		}
	}
	
	/**
	 * Run Application
	 *
	 * @return object
	 */
	public function run()
	{
        if ($this->container->get('EARequestConsoleStatusResult') == "Console") {
			
			//Console
			
			$matchedRouteResponse = $this->container->get('matchedRouteResponse');
			
			$this->matchedRouteKey = $this->container->get('MatchedRouteKey'); 
			
			$this->matchedRouteDetails = $this->container->get('MatchedRouteDetails'); 
			
			$requiredRouteType = "";
			$requiredRouteType = $this->matchedRouteDetails["route_type"];
			
			$pageStatus = $this->matchedRouteDetails["status"];
			$pageNumberOfRecords = $this->matchedRouteDetails["number_of_records"];
			$pageNumberOfLoopsCount = $this->matchedRouteDetails["number_of_loops_count"];
			$pageSleepTimeMinimumSeconds = $this->matchedRouteDetails["sleep_time_minimum_seconds"];
			$pageSleepTimeMaximumSeconds = $this->matchedRouteDetails["sleep_time_maximum_seconds"];
			$pageSleepIntervalDefinition = $this->matchedRouteDetails["sleep_interval_definition"];
			$pageFilename = $this->matchedRouteDetails["page_filename"];
			$pageRouteType = $this->matchedRouteDetails["route_type"];
			$pageControllerType = $this->matchedRouteDetails["controller_type"];
			$pageControllerClassName = $this->matchedRouteDetails["controller_class_name"];
			$pageMethodName = $this->matchedRouteDetails["method_name"];
			
			
			if ((isset($this->matchedRouteKey)) && ($this->matchedRouteKey != "not-found")) {
				
				if ($pageStatus == "ON") {
					
					if (($pageRouteType == "cron-job") || ($pageRouteType == "message-queue-worker")) {
						
						if ((isset($pageControllerType)) && (($pageControllerType == "procedural") || ($pageControllerType == "oop-mapped"))) {
					
							if (class_exists($pageControllerClassName)) {
								
								$matchedController = new $pageControllerClassName($this->container);
							
								$this->container->instance('MatchedControllerName', $matchedController);
								$this->matchedController = $this->container->get('MatchedControllerName');
								
								if ($this->matchedController->checkIfActionExists($pageMethodName)) {
																		
									$this->response = $this->matchedController->$pageMethodName();
									
									if ((isset($this->response)) && ($this->response == 0)) {
										//Success
										return $this->response;
										
									} elseif ((isset($this->response)) && ($this->response == 1)) {
										//FAILURE
										//Log Failure scenario content
										return $this->response;
										
									} else {
										//INVALID (>=2)
										//Log Invalid scenario reasons
										return $this->response;
										
									}
									
									
								} else {
								
									//throw new \Exception($pageMethodName . " action does not exist!");
									echo html_escaped_output($pageMethodName) . " action does not exist!";
								}
								
							} else {
								
								//throw new \Exception($pageControllerClassName . " controller does not exist!");
								echo html_escaped_output($pageControllerClassName) . " controller does not exist!";
							}
							
						}
						
					} else {
						
						echo "Cron jobs & Message queue workers are supported at this moment.\n";
						
					}
					
					
				} else {
					
					//throw new \Exception("Status of cli route is not ON. This trigger will be left to subside!\n");
					echo "Status of cli route is not ON. This trigger will be left to subside!\n";
				}
				
					
				
				
			} else {
				
				//throw new \Exception("cli route does not exist!\n");
				echo "cli route does not exist!\n";
				
			}
			
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