<?php
declare(strict_types=1);

namespace EaseAppPHP;

//$app = require_once __DIR__.'/../bootstrap/app.php';
//$app = require_once dirname(__FILE__).'/bootstrap/app.php';

use Illuminate\Container\Container;
use EaseAppPHP\Classes\EAConfig;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Middlewares\Utils\Dispatcher;
use Middlewares;

use Relay\Relay;

/**
 * App Class
 *
 */
 
class App
{
	private $app = array();
	private $container;
	private $middlewareQueue = array();
	private $config = array();
	private $psr17FactoryInstance;
	private $serverRequestCreatorInstance;
	private $psr15ServerRequestHandlerInstance;
	private $serverRequest;
	private $initResult;
	private $middlewareProcessedResponse;
	
	/**
	 * Accepts .env file path and loads the values into $ENV Superglobals. Also, Creates a Container.
	 *
	 * @param string $envFilePath
	 * @return object
	 */
	public function __construct($envFilePath){	

		//Load info from .env file
		$dotenv = \Dotenv\Dotenv::createImmutable($envFilePath);
		$dotenv->load();
		
		//Create Illuminate Container, outside Laravel Framework
		$this->container = Container::getInstance();
		
		return $this->container;
		
    }
	
	/**
	 * Extract Config Values into an array, Create ServerRequest, Server Providers
	 *
	 * @param string $configSource
	 * @param array $configSourceValueDataType
	 * @param array $configSourceValueData
	 * @param array $serviceClassNamesArray
	 * @return object
	 */
	public function init($configSource, $configSourceValueDataType, $configSourceValueData, $serviceClassNamesArray = array())
	{
		//Bind an existing "config" class instance to the container, by defining the Class Name as instance reference in the container
		$eaConfig = new EAConfig();
		$this->container->instance('EAConfig', $eaConfig);
		
		if (($configSource == 'As-Array') && ($configSourceValueDataType == 'array') && (is_array($configSourceValueData))) {
			
			$this->config = $this->container->make('EAConfig')->getAsArray($configSourceValueData);
			
		} else if (($configSource == 'From-Single-File') && ($configSourceValueDataType == 'string') && (is_string($configSourceValueData))) {
			
			$this->config = $this->container->make('EAConfig')->getFromSingleFile($configSourceValueData);
			
		} else if (($configSource == 'From-Single-Folder') && ($configSourceValueDataType == 'string') && (is_string($configSourceValueData))) {
			
			$this->config = $this->container->make('EAConfig')->getFromSingleFolder($configSourceValueData);
			
		} else if (($configSource == 'From-Filepaths-Array') && ($configSourceValueDataType == 'array') && (is_array($configSourceValueData))) {
			
			$this->config = $this->container->make('EAConfig')->getFromFilepathsArray($configSourceValueData);
			
		} else {
			
			echo "Throw an Exception\n";
			
		}
		
		//Create Objects
		$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
		$this->container->instance('\Nyholm\Psr7\Factory\Psr17Factory', $psr17Factory);
		
		$this->psr17FactoryInstance = $this->container->make('\Nyholm\Psr7\Factory\Psr17Factory');
		
		//$psr18Client = new \Buzz\Client\Curl($psr17Factory);

		//Create Server Requests
		$serverRequestCreator = new \Nyholm\Psr7Server\ServerRequestCreator(
			$this->psr17FactoryInstance, // ServerRequestFactory
			$this->psr17FactoryInstance, // UriFactory
			$this->psr17FactoryInstance, // UploadedFileFactory
			$this->psr17FactoryInstance  // StreamFactory
		);
		
		$this->container->instance('\Nyholm\Psr7Server\ServerRequestCreator', $serverRequestCreator);
		
		$this->serverRequestCreatorInstance = $this->container->make('\Nyholm\Psr7Server\ServerRequestCreator');
		
		$this->serverRequest = $this->serverRequestCreatorInstance->fromGlobals();
		
		$this->initResult[] = $this->config;
		$this->initResult[] = $this->serverRequest;
		
		return $this->initResult;
	}
	
	/**
	 * Process Server Request through Middlewares as per PSR-15 compatible Dispatcher
	 *
	 * @param object $serverRequest
	 * @param array $middlewareQueueArray
	 * @return object
	 */
	public function processServerRequest($serverRequest, $middlewareQueueArray = array())
	{
		if (count($middlewareQueueArray) > 0) {
			
			$psr15ServerRequestHandler = new Relay($middlewareQueueArray);
			$this->container->instance('Relay', $requestHandler);
			
			$this->psr15ServerRequestHandlerInstance = $this->container->make('Relay');
			
			$this->middlewareProcessedResponse = $this->psr15ServerRequestHandlerInstance->handle($serverRequest);
			
		} else {
			
			$this->middlewareProcessedResponse = $serverRequest;
			
		}
		
		/* 
		$queue[] = new ResponseFactoryMiddleware {
			return new Response();
		};

		$relay = new Relay($queue);
		$response = $relay->handle($serverRequest);

		var_dump($response); */
		
		return $this->middlewareProcessedResponse;
	}
	
	/**
     * Get response emitter
     *
     * @return \Laminas\Diactoros\Response\EmitterInterface
     */
    public function getResponseEmitter()
    {
        if (!$this->psr17FactoryInstance) {
            $this->psr17FactoryInstance = $this->container->make('\Nyholm\Psr7\Factory\Psr17Factory');
        }

        $responseBody = $this->psr17FactoryInstance->createStream('Hello world');
		$response = $this->psr17FactoryInstance->createResponse(200)->withBody($responseBody);
		
		(new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter())->emit($response);
        //return $contract;
    } 	
}
?>