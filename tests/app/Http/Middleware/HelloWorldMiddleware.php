<?php
declare(strict_types=1);

namespace EaseAppPHP\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HelloWorldMiddleware implements MiddlewareInterface
{
    private $outer4;
	private $html;
	
	public function __construct($outer4, $html) {
		$this->outer4 = $outer4;
		$this->html = $html;
	}
	
	public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) : ResponseInterface {
        // ... do something and return response
        // or call request handler:
        // return $handler->handle($request);
		//return $handler->handle($request);
		
		if ($this->outer4 == "Shankara") {
						
			//$response = new \Laminas\Diactoros\Response\TextResponse($this->outer2);
			$response = new \Laminas\Diactoros\Response\HtmlResponse($this->outer4. ":::::" . $this->html);
			return $response;
			
		} else {
			
			$response = $handler->handle($request);
			return $response->withAddedHeader('Fourth-Middleware-Header', 'my-value3');
			
			
		}
		
    }
}