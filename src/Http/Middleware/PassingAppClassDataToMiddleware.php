<?php
declare(strict_types=1);

namespace EaseAppPHP\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PassingAppClassDataToMiddleware implements MiddlewareInterface
{
    private $appClassData;
	
	public function __construct($appClassData) {
		$this->appClassData = $appClassData;
	}
	
	public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) : ResponseInterface {
        // ... do something and return response
        // or call request handler:
        // return $handler->handle($request);
		//return $handler->handle($request);
		
		if (isset($this->appClassData)) {
						
			// Step 2: Inject data into the request, call the next middleware and wait for the response
			$response = $handler->handle($request->withAttribute(self::class, $this->appClassData));
			return $response;
			
		} else {
			
			$response = $handler->handle($request);
			return $response;
			
			
		}
		
    }
}