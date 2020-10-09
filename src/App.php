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
            /*$appServiceProvider = new \EaseAppPHP\Providers\AppServiceProvider($this->container);
            $appServiceProvider->register();
            
            $routeServiceProvider = new \EaseAppPHP\Providers\RouteServiceProvider($this->container);
            $routeServiceProvider->register();*/
            
            //Loop through and Register Service Providers First
            foreach ($this->getConfig()["first-config"]["providers"] as $serviceProvidersArrayRowKey => $serviceProvidersArrayRowValue) {
                
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
            //echo "easerviceproviders:<br>";
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
            //echo "ealoadedserviceproviderslist:<br>";
            //var_dump($this->eaLoadedServiceProvidersList);

		
		
            
		
            /* //Define Laminas Stratigility Middlewarepipe
             $middlewarePipe = new \Laminas\Stratigility\MiddlewarePipe();  // API middleware collection
             $this->container->instance('\Laminas\Stratigility\MiddlewarePipe', $middlewarePipe);
             $this->middlewarePipeQueue = $this->container->get('\Laminas\Stratigility\MiddlewarePipe');

           */

            
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
                
                $this->routesList = $this->container->get('routes');
                $this->matchedRouteResponse = $this->container->get('matchedRouteResponse');
                $this->middlewarePipeQueueEntries = $this->container->get('middlewarePipeQueueEntries');
                
                //var_dump($this->routeslist);                
                //var_dump($this->container->get('matchedRouteResponse'));
                //echo "<pre>";
                //print_r($this->middlewarePipeQueueEntries);
                //exit;
                
                $required_matched_page_filename = $this->container->get('matchedRouteResponse')["matched_page_filename"];
                
                foreach($this->routesList as $key => $value){
                    if($key ==  $required_matched_page_filename){
                        print_r($value);
                        $required_route_type = $value["route_type"];
                        echo "required_route_type: " . $required_route_type . "<br>\n";
                        $required_with_middleware = $value["with_middleware"];
                        echo "required_with_middleware: " . $required_with_middleware . "<br>\n";
                        $required_without_middleware = $value["without_middleware"];
                        echo "required_without_middleware: " . $required_without_middleware . "<br>\n";
                        if($required_with_middleware != ""){
                            $required_with_middleware_array = explode(",", $required_with_middleware);
                        }
                        if($required_without_middleware != ""){
                            $required_without_middleware_array = explode(",", $required_without_middleware);
                        }
                        break;
                    }
                }

                if ($required_matched_page_filename != "header-response-only-404-not-found") {
                    //oop_mapped controller or procedural controller
                    
                } else {
                    //do automated check for oop controller enumeration
                    
                    if ("oop" == "success") {
                        //oop enumeration success
                    } else {
                        //echo "404 error";
                    }
                }

                // RUN MIDDLEWARE using HTTPHandlerRunner Laminas Library
                //https://docs.laminas.dev/laminas-stratigility/v3/middleware/#middleware
                $requestHandlerRunnerServer = new \Laminas\HttpHandlerRunner\RequestHandlerRunner(
                        $this->container->get('middlewarePipeQueueEntries'),
                        new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter(),
                        static function () {
                                return \Laminas\Diactoros\ServerRequestFactory::fromGlobals();
                                //return $this->container->get('\Laminas\Diactoros\ServerRequestFactory');
                                //return $this->serverRequest;
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
                //$requestHandlerRunnerServer->run();

                $this->container->instance('\Laminas\HttpHandlerRunner\RequestHandlerRunner', $requestHandlerRunnerServer);
                $this->requestHandlerRunnerServerInstance = $this->container->get('\Laminas\HttpHandlerRunner\RequestHandlerRunner');

                $this->requestHandlerRunnerServerInstance->run();


                /*  //https://docs.laminas.dev/laminas-httphandlerrunner/emitters/
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

                //print_r($this->emitterStackInstance);

                $this->emitterStackInstance->push(new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter());
                $this->emitterStackInstance->push($conditionalEmitter);


                //print_r($this->emitterStackInstance);
                //var_dump($conditionalEmitter);
                 */

                //var_dump($this->middlewareProcessedResponse);
                /* echo 'HTTP_HOST: ' . $this->serverRequest->getServerParams()['HTTP_HOST'];

                if (isset($this->serverRequest->getQueryParams()["user"])) {
                        $query_param   = $this->serverRequest->getQueryParams()["user"];
                }


                $query   = $this->serverRequest->getQueryParams();
                echo "<br>query: " . "<br>";
                print_r($query);
                echo "<br>";  
                 */

            }
            

	}
        
        /**
        * Get the config array of the application.
        *
        * @return string
        */
        public function getConfig()
        {
            //return $this->config["first-config"];
            //echo "<pre>";
            //var_dump($this->config);
            return $this->config;
            //return $this->container->get('config');
            //return $this->config["first-config"]["providers"];
            //return $this->config["first-config"]["name"];
            //return $this->config["first-config"]["routing_engine_rule_files"];
            //not working return $this->serverRequest->getUri()->getPath();
            //not working return $this->serverRequest->getQueryParams();
            //not working return $this->serverRequest->getMethod();
            //not working return $this->config["first-config"]["routing_rule_length"];
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
?>