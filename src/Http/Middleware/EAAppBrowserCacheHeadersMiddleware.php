<?php
declare(strict_types=1);

namespace EaseAppPHP\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EAAppBrowserCacheHeadersMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) : ResponseInterface {
        
		// Step 1: Grab the data from the request and use it
        $dataFromAppClass = $request->getAttribute(PassingAppClassDataToMiddleware::class);
		
		// Step 1: Do something first
		$data = [
            'foo' => 'bar',
        ]; 
		
		
		$result = array_merge($dataFromAppClass, $data);

       // Step 2: Inject data into the request, call the next middleware and wait for the response
        $response = $handler->handle($request->withAttribute(PassingAppClassDataToMiddleware::class, $result));


		//$response = $handler->handle($request);
		
		// Set accept header
		//$this->response = $this->response->withAddedHeader('Allow', implode(', ', $methods));
		
		/* for php websites
		if (!$response->hasHeader('Content-Type')) {
			$response = $response->withHeader('Content-Type', 'text/html; charset=UTF-8');
		}
		
		if (!$response->hasHeader('Expires')) {
			$response = $response->withHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
		}
		 */
		
		if (!$response->hasHeader('Last-Modified')) {
			$response = $response->withHeader('Last-Modified', gmdate( 'D, d M Y H:i:s' ) . ' GMT');
		}
		
		if (!$response->hasHeader('Cache-Control')) {
			$response = $response->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate');
		}
		
		if ($response->hasHeader('Cache-Control')) {
			$response = $response->withAddedHeader('Cache-Control', 'pre-check=0', false); //post-check=0 is removed, as per guidelines of Fiddler 
		}
		
		/* if (!$response->hasHeader('Pragma')) {
			$response = $response->withHeader('Pragma', 'no-cache'); //this Pragma header is commented, as this will be useful only on IE Browser, as suggested in Fiddler.
		} */
		
		/* $response = $response->withAttribute('fromBrowserCacheHeadersmiddleware', 'Yes');		
		$requestinBrowserCacheHeadersmiddleware = $response->getAttribute("fromBrowserCacheHeadersmiddleware");
		echo "requestinBrowserCacheHeadersmiddleware: " . $requestinBrowserCacheHeadersmiddleware . "<br>";
		 */
		//echo "Browser Cache Headers";
		return $response;
		
    }
	
}