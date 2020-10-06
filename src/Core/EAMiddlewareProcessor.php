<?php
declare(strict_types=1);

namespace EaseAppPHP\Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Stratigility\MiddlewarePipe;

/**
 * EAMiddlewareProcessor Class
 *
 */
 
class EAMiddlewareProcessor implements \Psr\Http\Server\MiddlewareInterface
{
    private $pipeline;

    public function __construct(object $configuration, MiddlewarePipe $pipeline)
    {
        // do something with configuration ...

        // attach some middleware ...
        $pipeline->pipe(/* some middleware */);

        $this->pipeline = $pipeline;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        /* ... */
    }
			
}
?>