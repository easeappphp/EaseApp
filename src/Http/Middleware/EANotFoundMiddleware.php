<?php
declare(strict_types=1);

namespace EaseAppPHP\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EANotFoundMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) : ResponseInterface {
        
		
        //$request->getserverParams()['HTTP_HOST'];
		$response = $response->withStatus(404);
                $response->getBody()->write(
                    'error::404 in ' . $request->getserverParams()['HTTP_HOST']
                );
		
		//$response = $handler->handle($request);
		
		
		//echo "404 - Not Found Handler";
		return $response;
		
    }
	
}