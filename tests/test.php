<?php 
declare(strict_types=1);

//namespace EaseAppPHP;
//To prevent direct access to a file inside public root or public_html or www folder, 
define("START", "No Direct Access", true);

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Illuminate\Container\Container;
use EaseAppPHP\App;
use EaseAppPHP\Classes\EAConfig;

//use ParagonIE\Halite\File;
use ParagonIE\Halite\KeyFactory;
use ParagonIE\Halite\Symmetric\Crypto as Symmetric;
use ParagonIE\HiddenString\HiddenString;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/* use Middlewares\Utils\Dispatcher;
use Middlewares; */

use Relay\Relay;

$serviceClassNamesArray = array();
$middlewareQueueArray = array();

$app = new App(dirname(__FILE__));
$singleConfigFilePath = dirname(__FILE__).'/config/first-config.php';

$appInitResult = $app->init('From-Single-File', 'string', $singleConfigFilePath, $serviceClassNamesArray = array());

$config = $appInitResult[0];
$serverRequest = $appInitResult[1];	

$serverRequestResponse = $app->processServerRequest($serverRequest, $middlewareQueueArray = array());		


if (version_compare(PHP_VERSION, '7.2.0') >= 0) {
	$appBasePath  = $serverRequest->getserverParams()["APP_BASE_PATH"];
	//echo "appBasePath: " . $appBasePath . "<br>";
	
	$appWebRootPath  = $serverRequest->getserverParams()["APP_WEB_ROOT_PATH"];
	//echo "appWebRootPath: " . $appWebRootPath . "<br>";
	
	
	$method  = $serverRequest->getMethod();
	//echo "method: " . $method . "<br>";

	//irrespective of query string, this is path value from the uri
	$path    = $serverRequest->getUri()->getPath();
	//echo "path: " . $path . "<br>";


	$accept  = $serverRequest->getHeaderLine('Accept');
	//echo "accept: " . $accept . "<br>";


	//$data    = json_decode((string) $serverRequest->getBody());
	//echo "data: " . $data . "<br>";

	//echo "server params:<br>";
	//print_r($serverRequest->getserverParams());
	//echo "REQUEST_URI: " . $serverRequest->getserverParams()["REQUEST_URI"] . "<br>";
	//echo "APP_NAME: " . $serverRequest->getserverParams()["APP_NAME"] . "<br>";

	$query   = $serverRequest->getQueryParams();
	/* echo "query: " . "<br>";
	print_r($query);
	echo "<br>"; */
	
	
	if (isset($serverRequest->getQueryParams()["user"])) {
		$query_param   = $serverRequest->getQueryParams()["user"];
	}
	

}

//Emit Response
$app->getResponseEmitter();



