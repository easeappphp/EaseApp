<?php
declare(strict_types=1);

namespace EaseAppPHP\Http\Middleware;

//use Illuminate\Container\Container;
use EaseAppPHP\Http\Middleware\Kernel;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EARouterMiddleware implements MiddlewareInterface
{
    //private $eaRouter;
	//private $eaRouterUriPathParams;
	private $routes;
	private $matchedRouteResponse;
	
	public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) : ResponseInterface {
        // ... do something and return response
        // or call request handler:
        // return $handler->handle($request);
		//return $handler->handle($request);
		
		// Step 1: Grab the data from the request and use it
        $dataFromAppClass = $request->getAttribute(PassingAppClassDataToMiddleware::class);
		
		echo "<pre>";
		//print_r($dataFromAppClass); 
		/*
		echo $dataFromAppClass["student"] . "<br>";
		
		print_r($dataFromAppClass["matchedRouteResponse"]);
		 */
		
		$this->routes = $dataFromAppClass["routes"];
		$this->matchedRouteResponse =  $dataFromAppClass["matchedRouteResponse"];
		
		if (count($this->matchedRouteResponse) == 3) {
			$page_auth_check_requirements = $this->routes[$this->matchedRouteResponse["matched_page_filename"]]["auth_check_requirements"];
			$page_filename = $this->routes[$this->matchedRouteResponse["matched_page_filename"]]["page_filename"];
			$page_redirect_to = $this->routes[$this->matchedRouteResponse["matched_page_filename"]]["redirect_to"];
			$page_route_type = $this->routes[$this->matchedRouteResponse["matched_page_filename"]]["route_type"];
			$allowed_request_method = $this->routes[$this->matchedRouteResponse["matched_page_filename"]]["allowed_request_method"];  
			$route_filename = substr($page_filename, 0, -4);
			
			$received_request_method = $this->matchedRouteResponse["received_request_method"];
			$original_route_rel_request_method = $this->matchedRouteResponse["original_route_rel_request_method"];
			
			echo "page_auth_check_requirements: " . $page_auth_check_requirements . "\n<br>";
			echo "page_filename: " . $page_filename . "\n<br>";
			echo "page_redirect_to: " . $page_redirect_to . "\n<br>";
			echo "page_route_type: " . $page_route_type . "\n<br>";
			echo "allowed_request_method: " . $allowed_request_method . "\n<br>";
			echo "route_filename: " . $route_filename . "\n<br>";
			echo "received_request_method: " . $received_request_method . "\n<br>";
			echo "original_route_rel_request_method: " . $original_route_rel_request_method . "\n<br>";
			
			 
			global $route_filename;	
		}
		/*
		1) Get URLPathParams
		2) Get Routes array
		3) match Route
		4) Check and Process injected Global Middleware & Route specific Middleware, with additional middlewaregroups, middlewarepriority and other scenarios as part of eaRouteMiddleware
		
		
		
		*/
		//print_r($dataFromAppClass["httpKernel"]);
		//print_r($kernelInstance->middleware);
		print_r($dataFromAppClass["httpKernel"]->middleware);
		if (count($dataFromAppClass["httpKernel"]->middleware) > 0) {
			
			foreach ($dataFromAppClass["httpKernel"]->middleware as $key => $value) {
				
				echo "key: " . $key . "<br>";
				echo "value: " . $value . "()" . "<br>";
				$className = $value();
				$app->middlewarePipeQueue->pipe(new $value());
				
				
			}
			
		}   
		//echo "<pre>";
		//print_r($request);
		/* $request = $request->withAttribute('isAjaxRequest', true);
		echo $request->isAjaxRequest; */
		//$firstrouteinmiddleware = $request->getAttribute("firstroute");
		/* echo "firstrouteinmiddleware: " . $firstrouteinmiddleware . "<br>";
		
		
		$request = $request->withAttribute('frommiddleware', 'routename');
		$frommiddleware = $request->getAttribute("frommiddleware");
		echo "frommiddleware: " . $frommiddleware . "<br>";
		
		$fromBrowserCacheHeadersmiddleware = $request->getAttribute("fromBrowserCacheHeadersmiddleware");
		echo "fromBrowserCacheHeadersmiddleware: " . $fromBrowserCacheHeadersmiddleware . "<br>";
		 */
		//$this->eaRouterUriPathParams = $this->eaRouter->getUriPathParams($request->getServerParams()['REQUEST_URI']);
		//echo "<pre>";
		//print_r($this->eaRouterUriPathParams);
		
		/* $routes = $eaRouter->getFromFilepathsArray($config["routing_engine_rule_files"]);

		$matchedRouteResponse = $eaRouter->matchRoute($routes, $serverRequest->getUri()->getPath(), $serverRequest->getQueryParams(), $serverRequest->getMethod(), $config["routing_rule_length"]);

		if (count($matchedRouteResponse) == 3) {
			$page_auth_check_requirements = $routes[$matchedRouteResponse["matched_page_filename"]]["auth_check_requirements"];
			$page_filename = $routes[$matchedRouteResponse["matched_page_filename"]]["page_filename"];
			$page_redirect_to = $routes[$matchedRouteResponse["matched_page_filename"]]["redirect_to"];
			$page_route_type = $routes[$matchedRouteResponse["matched_page_filename"]]["route_type"];
			$allowed_request_method = $routes[$matchedRouteResponse["matched_page_filename"]]["allowed_request_method"];  
			$route_filename = substr($page_filename, 0, -4);
			
			$received_request_method = $matchedRouteResponse["received_request_method"];
			$original_route_rel_request_method = $matchedRouteResponse["original_route_rel_request_method"];
			
			 echo "page_auth_check_requirements: " . $page_auth_check_requirements . "\n<br>";
			echo "page_filename: " . $page_filename . "\n<br>";
			echo "page_redirect_to: " . $page_redirect_to . "\n<br>";
			echo "page_route_type: " . $page_route_type . "\n<br>";
			echo "allowed_request_method: " . $allowed_request_method . "\n<br>";
			echo "route_filename: " . $route_filename . "\n<br>";
			echo "received_request_method: " . $received_request_method . "\n<br>";
			echo "original_route_rel_request_method: " . $original_route_rel_request_method . "\n<br>";
			 
			 
			global $route_filename;	
		} */



		
		
	/* 	if ($this->outer4 == "Shankara") {
						
			//$response = new \Laminas\Diactoros\Response\TextResponse($this->outer2);
			$response = new \Laminas\Diactoros\Response\HtmlResponse($this->eaRouterUriPathParams[3]);
			return $response;
			
		} else {
			
			$response = $handler->handle($request);
			return $response->withAddedHeader('Fourth-Middleware-Header', 'my-value3');
			
			
		} */
		
    }
}