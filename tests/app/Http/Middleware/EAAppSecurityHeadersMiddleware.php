<?php
declare(strict_types=1);

namespace EaseAppPHP\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EAAppSecurityHeadersMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) : ResponseInterface {
        
		// Step 1: Grab the data from the request and use it
        $data = $request->getAttribute(PassingAppClassDataToMiddleware::class);
		
		/* echo "<pre>";
		print_r($data); */
		
		/* // Step 1: Grab the data from the request and use it
        $data1234 = $request->getAttribute(EaseAppPHP\App::class);
		
		echo "<pre>";
		print_r($data1234);  
		 */
		//var_dump($request);
		
        // Step 2: Call the next middleware and wait for the response
        $response = $handler->handle($request);	
		//$response = $handler->handle($request);
		
		//Prevent Mimetype Sniffing
		if (!$response->hasHeader('x-content-type-options')) {
			$response = $response->withHeader('x-content-type-options', 'nosniff');
		}
		
		//XSS Protection
		if (!$response->hasHeader('X-XSS-Protection')) {
			$response = $response->withHeader('X-XSS-Protection', '1; mode=block');
		}
		
		/* //Clickjacking Prevention, while allowing to iframe the page from sameorigin in php
		if (!$response->hasHeader('X-Frame-Options')) {
			$response = $response->withHeader('X-Frame-Options', 'SAMEORIGIN', false);
		} */
		
		//Clickjacking Prevention overall without allowing sameorigin or a different origin from iframing the page in php
		if (!$response->hasHeader('X-Frame-Options')) {
			$response = $response->withHeader('X-Frame-Options', 'DENY');
		}
		
		//Remove PHP Information (Version) Header (THIS IS NOT WORKING, NEED TO CHECK TO REMOVE HEADER METHOD AGAIN)
		if (!$response->hasHeader('x-powered-by')) {
			$response = $response->withoutHeader('x-powered-by');
		}
		
		//This Header is to allow flash to share client side data between its applications in PHP
		if (!$response->hasHeader('X-Permitted-Cross-Domain-Policies')) {
			$response = $response->withHeader('X-Permitted-Cross-Domain-Policies', 'master-only');
		}
		
		 
		//echo "Application Security Headers";
		return $response;
		
    }
	
}