<?php 
declare(strict_types=1);
namespace EaseAppPHP\Application;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


//namespace EaseAppPHP;
//To prevent direct access to a file inside public root or public_html or www folder, 
define("START", "No Direct Access");

/*
|--------------------------------------------------------------------------
| Check If Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is maintenance / demo mode via the "down" command we
| will require this file so that any prerendered template can be shown
| instead of starting the framework, which could cause an exception.
|
*/

/* if (file_exists(__DIR__.'/../storage/framework/maintenance.php')) {
    require __DIR__.'/../storage/framework/maintenance.php';
} */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require_once __DIR__ . '/../../vendor/autoload.php'; // Autoload files using Composer autoload

use Illuminate\Container\Container;
use EaseAppPHP\App;

//use ParagonIE\Halite\File;
use ParagonIE\Halite\KeyFactory;
use ParagonIE\Halite\Symmetric\Crypto as Symmetric;
use ParagonIE\HiddenString\HiddenString;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use EARouter\EARouter;

// In public/index.php:
use Laminas\Diactoros\Response;
use Laminas\Stratigility\Middleware\ErrorHandler;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Laminas\Stratigility\Middleware\NotFoundHandler;

use Psr\Http\Message\ResponseInterface;

//use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Stratigility\MiddlewarePipe;


use Psr\Log\LoggerInterface;
use Laminas\Diactoros\Response\EmptyResponse;

use function Laminas\Stratigility\middleware;
use function Laminas\Stratigility\path;

//use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;


/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/
//$app = new \EaseAppPHP\App($_ENV['APP_BASE_PATH'] ?? dirname(dirname(__FILE__)));
$container = require_once __DIR__.'/../bootstrap/app.php';

$envFilePath = dirname(dirname(__FILE__));
$singleFolderConfigFilePath = dirname(dirname(__FILE__)).'/config';

$application = new App($envFilePath, $container, 'From-Single-Folder', 'string', $singleFolderConfigFilePath);
$container->instance('App', $application);

$app = $container->get('App');

$app->init();

$app->run();



