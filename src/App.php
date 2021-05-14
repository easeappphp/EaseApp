<?php
declare(strict_types=1);

namespace EaseAppPHP;

use Illuminate\Container\Container;
use EaseAppPHP\Core\EAConfig;
use EaseAppPHP\Core\EAIsConsole;
//use EaseAppPHP\Core\EAHandleData;
//use EaseAppPHP\Http\Middleware\Kernel;
//use EaseAppPHP\Core\EAMiddlewareProcessor;

//use Middlewares;

//use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

//use Psr\Http\Message\ResponseInterface;

//use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Stratigility\MiddlewarePipe;


use Psr\Log\LoggerInterface;
use Laminas\Diactoros\Response\EmptyResponse;

/* use EaseAppPHP\Middleware\HelloWorld123Middleware;
use EaseAppPHP\Middleware\HelloWorldMiddleware;
 */
//use EARouter\EARouter;
use \EaseAppPHP\Foundation\BaseApplication;
//use \EaseAppPHP\Foundation\ServiceProvider;
//use \EaseAppPHP\Providers\AppServiceProvider;
//use \EaseAppPHP\Providers\RouteServiceProvider;
 

/**
 * App Class
 *
 */
 
//class App
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
	//protected $psr15ServerRequestHandlerInstance;
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
	
	/**
	* All of the registered service providers.
	*
	* @var \Illuminate\Support\ServiceProvider[]
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
	public function __construct($envFilePath, $container, $configSource, $configSourceValueDataType, $configSourceValueData)
        //public function __construct($container)
        {	
            $this->container = $container;
			
			//Load info from .env file
            $dotenv = \Dotenv\Dotenv::createImmutable($envFilePath);
            $dotenv->load();
			
			//Create a Server Request using Laminas\Diactoros PSR-7 Library
            // Returns new ServerRequest instance, using values from superglobals:
            $serverRequestInstance = \Laminas\Diactoros\ServerRequestFactory::fromGlobals();

            //Bind an existing "serverRequest" class instance to the container, by defining the Class Name as instance reference in the container
            $this->container->instance('\Laminas\Diactoros\ServerRequestFactory', $serverRequestInstance);
			
			$this->serverRequest = $this->container->get('\Laminas\Diactoros\ServerRequestFactory');
			
			//Create Whoops Error & Exception Handler object
			$whoops = new \Whoops\Run();
			$this->container->instance('\Whoops\Run', $whoops);
		
			//Create a Response Object
			$responseInstance = new \EaseAppPHP\Foundation\BaseWebResponse($container);

			//Bind an existing "response" class instance to the container, by defining the Class Name as instance reference in the container
			$this->container->instance('\EaseAppPHP\Foundation\BaseWebResponse', $responseInstance);
			
			$this->response = $this->container->get('\EaseAppPHP\Foundation\BaseWebResponse');
            
            
            //Bind an existing "config" class instance to the container, by defining the Class Name as instance reference in the container
            $eaConfig = new EAConfig();
            $this->container->instance('EAConfig', $eaConfig);
            
            if (($configSource == 'As-Array') && ($configSourceValueDataType == 'array') && (is_array($configSourceValueData))) {

                    $this->collectedConfigData = $this->container->get('EAConfig')->getAsArray($configSourceValueData);

            } else if (($configSource == 'From-Single-File') && ($configSourceValueDataType == 'string') && (is_string($configSourceValueData))) {

                    $this->collectedConfigData = $this->container->get('EAConfig')->getFromSingleFile($configSourceValueData);

            } else if (($configSource == 'From-Single-Folder') && ($configSourceValueDataType == 'string') && (is_string($configSourceValueData))) {

                    $this->collectedConfigData = $this->container->get('EAConfig')->getFromSingleFolder($configSourceValueData);

            } else if (($configSource == 'From-Filepaths-Array') && ($configSourceValueDataType == 'array') && (is_array($configSourceValueData))) {

                    $this->collectedConfigData = $this->container->get('EAConfig')->getFromFilepathsArray($configSourceValueData);

            }

            $this->container->instance('config', $this->collectedConfigData);                
            $this->config = $this->container->get('config');   
            
            
            /*//EAHandleData Class
            $eaHandleData = new EAHandleData($container);
            $this->container->instance('EAHandleData', $eaHandleData);
            
            //$eaHandleData::set("AZ","12");
            //echo "AZ GET: " . $eaHandleData::get("AZ");*/
            
            
            //Check if the request is based upon Console or Web
            $eaIsConsole = new EAIsConsole();
            $this->container->instance('EAIsConsole', $eaIsConsole);
            $this->eaIsConsoleInstance = $this->container->get('EAIsConsole')->checkSTDIN();
            
            //Save EA REQUEST Console Status Result to Container
            $this->container->instance('EARequestConsoleStatusResult', $this->eaIsConsoleInstance);
            $this->eaRequestConsoleStatusResult = $this->container->get('EARequestConsoleStatusResult');        
            
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
                if ($this->serverRequest->getServerParams()['APP_DEBUG'] == "true") {
					
					$whoopsHandler = $this->container->get('\Whoops\Run');
					$whoopsHandler->pushHandler(new \Whoops\Handler\PlainTextHandler());
					$whoopsHandler->register();
					
				}
				
            } else {
            
                //Web
				if ($this->serverRequest->getServerParams()['APP_DEBUG'] == "true") {
					
					//Note: Plaintexthandler to be defined for logging additionally
					$whoopsHandler = $this->container->get('\Whoops\Run');
					$whoopsHandler->pushHandler(new \Whoops\Handler\PrettyPageHandler());
					//$whoopsHandler->pushHandler(new \Whoops\Handler\PlainTextHandler());
					//$whoopsHandler->pushHandler(new \Whoops\Handler\XmlResponseHandler());
					//$whoopsHandler->pushHandler(new \Whoops\Handler\JsonResponseHandler());
					$whoopsHandler->register();
					
					
					//throw new \RuntimeException("Oopsie!");
					
				} else {
					
					$whoopsHandler = $this->container->get('\Whoops\Run');
					$whoopsHandler->pushHandler(new \Whoops\Handler\PlainTextHandler());
					$whoopsHandler->register();
					
				}
				
				/*$appServiceProvider = new \EaseAppPHP\Providers\AppServiceProvider($this->container);
				$appServiceProvider->register();
				
				$routeServiceProvider = new \EaseAppPHP\Providers\RouteServiceProvider($this->container);
				$routeServiceProvider->register();*/
				
				//Loop through and Register Service Providers First
				foreach ($this->getConfig()["mainconfig"]["providers"] as $serviceProvidersArrayRowKey => $serviceProvidersArrayRowValue) {
					
					//echo "$serviceProvidersArrayRowKey: " . $serviceProvidersArrayRowKey . "\n";
					//echo "$serviceProvidersArrayRowValue: " . $serviceProvidersArrayRowValue . "\n";
					
					$registeredServiceProviders[$serviceProvidersArrayRowKey] = new $serviceProvidersArrayRowValue($this->container);
					$registeredServiceProviders[$serviceProvidersArrayRowKey]->register();
					//echo $serviceProvidersArrayRowValue;
					$this->serviceProviders[] = $serviceProvidersArrayRowValue; // NOT WORKING STILL
					
					//Save available Serviceproviders to Container
					$this->container->instance('EAServiceProviders', $this->serviceProviders);
					$this->eaServiceProvidersList = $this->container->get('EAServiceProviders'); 
					
				}
				//echo "easerviceproviders:";
				// var_dump($this->eaServiceProvidersList);
				//Loop through and Boot Service Providers Next
				foreach ($this->eaServiceProvidersList as $serviceProvidersArrayRowKey => $serviceProvidersArrayRowValue) {
					
					//echo "$serviceProvidersArrayRowKey: " . $serviceProvidersArrayRowKey . "\n";
					//echo "$serviceProvidersArrayRowValue: " . $serviceProvidersArrayRowValue . "\n";
					
					//$regiseredServiceProviders[$serviceProvidersArrayRowKey] = new $serviceProvidersArrayRowValue($this->container);
					$registeredServiceProviders[$serviceProvidersArrayRowKey]->boot();
					
					//https://stackoverflow.com/questions/829823/can-you-create-instance-properties-dynamically-in-php
					//https://stackoverflow.com/questions/33486639/create-an-object-inside-for-loop

					$this->loadedProviders[] = $serviceProvidersArrayRowValue; // NOT WORKING STILL
					
					//Save available Serviceproviders to Container
					$this->container->instance('EALoadedServiceProviders', $this->loadedProviders);
					$this->eaLoadedServiceProvidersList = $this->container->get('EALoadedServiceProviders'); 
					
				}
				//echo "ealoadedserviceproviderslist:";
				//var_dump($this->eaLoadedServiceProvidersList);
				
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