<?php
declare(strict_types=1);

namespace EaseAppPHP\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EACorsMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) : ResponseInterface {
        
		$response = $handler->handle($request);
		
		// Allow from any origin
		if (isset($request->getserverParams()['HTTP_ORIGIN'])) {
			
			if (!$response->hasHeader('Access-Control-Allow-Origin')) {
				$response = $response->withHeader('Access-Control-Allow-Origin', '*');
			}
			
			if (!$response->hasHeader('Access-Control-Allow-Credentials')) {
				$response = $response->withHeader('Access-Control-Allow-Credentials', 'true');
			}
			
			if (!$response->hasHeader('Access-Control-Max-Age')) {
				$response = $response->withHeader('Access-Control-Max-Age', '86400');
			}
			
			if (!$response->hasHeader('Access-Control-Expose-Headers')) {
				$response = $response->withHeader('Access-Control-Expose-Headers', 'authorization');
			}
		
		}

		// Access-Control headers are received during OPTIONS requests
		if ($request->getserverParams()['REQUEST_METHOD'] == 'OPTIONS') {

			if (isset($request->getserverParams()['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
				
				// may also be using PUT, PATCH, HEAD etc
				if (!$response->hasHeader('Access-Control-Allow-Methods')) {
					$response = $response->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
				}
				
			if (isset($request->getserverParams()['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				
				if (!$response->hasHeader('Access-Control-Allow-Headers')) {
					$response = $response->withHeader('Access-Control-Allow-Headers', "{$request->getserverParams()['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
				}
				return $response;
			exit(0);
		}

		//echo "You have CORS Enabled!";
		return $response;
		
    }
	
}