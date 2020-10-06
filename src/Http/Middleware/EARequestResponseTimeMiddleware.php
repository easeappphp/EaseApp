<?php
declare(strict_types=1);

namespace EaseAppPHP\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EARequestResponseTimeMiddleware implements MiddlewareInterface
{
    private const RESPONSEHEADER = 'X-Response-Time';
    private $startTime;
	
	public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) : ResponseInterface {
	
	$this->startTime = isset($request->getserverParams()['REQUEST_TIME_FLOAT']) ? $request->getserverParams()['REQUEST_TIME_FLOAT'] : microtime(TRUE);
        $response = $handler->handle($request);

        $response = $response->withHeader(self::RESPONSEHEADER, sprintf('%2.3fms', (microtime(TRUE) - $this->startTime) * 1000));
        return $response;
		
    }
	
}