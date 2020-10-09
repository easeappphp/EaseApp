<?php
declare(strict_types=1);

namespace EaseAppPHP\Providers;

use \EaseAppPHP\Foundation\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $container;
    protected $eaRouterinstance;
    protected $config;
    protected $serverRequest;
    protected $eaRequestConsoleStatusResult;
    protected $routes;
    protected $matchedRouteResponse;
    private $middlewarePipeQueue;
    private $constructedResponse = [];
    
    /**
     * Create a new Illuminate application instance.
     *
     * @param  string|null  $basePath
     * @return void
     */
    public function __construct($container)
    {
        $this->container = $container;
    }   
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->container->get('EARequestConsoleStatusResult') == "Web") {
            
            $eaRouter = new \EARouter\EARouter();
            $this->container->instance('\EARouter\EARouter', $eaRouter);
        
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->container->get('EARequestConsoleStatusResult') == "Web") {
            
            $this->eaRouterinstance = $this->container->get('\EARouter\EARouter');
        
            $this->config = $this->container->get('config');
            $this->serverRequest = $this->container->get('\Laminas\Diactoros\ServerRequestFactory');



            //WORKING var_dump($this->container->get('config')["first-config"]["routing_engine_rule_files"]);
            //TO TRY var_dump(getDataFromContainer('config')["first-config"]["routing_engine_rule_files"]);

            //Get Routes from /routes folder w.r.t. web, ajax, ajax-api-common, rest-api, soap-api related files. This scenario excludes CLI and Channels primarily.
            $this->routes = $this->eaRouterinstance->getFromFilepathsArray($this->config["first-config"]["routing_engine_rule_files"]);
            //var_dump($this->routes);

            //Match Route			
            $this->matchedRouteResponse = $this->eaRouterinstance->matchRoute($this->routes, $this->serverRequest->getUri()->getPath(), $this->serverRequest->getQueryParams(), $this->serverRequest->getMethod(), $this->config["first-config"]["routing_rule_length"]);
            //var_dump($this->matchedRouteResponse);
            // Step 1: Do something first
            /*$appClassData = [
                    'config' => $this->config,
                    'routes' => $this->routes,
                    'eaRouterinstance' => $this->eaRouterinstance,
                    'matchedRouteResponse' => $this->matchedRouteResponse,
                    //'httpKernel' => $this->kernelInstance,
            ];*/
            
            //Define Laminas Stratigility Middlewarepipe
            $middlewarePipe = new \Laminas\Stratigility\MiddlewarePipe();  // API middleware collection
            $this->container->instance('\Laminas\Stratigility\MiddlewarePipe', $middlewarePipe);
            $this->middlewarePipeQueue = $this->container->get('\Laminas\Stratigility\MiddlewarePipe');
            //var_dump($this->middlewarePipeQueue);
            
            echo "<pre>";
            var_dump($this->config["middleware"]["middleware"]);
            
            foreach ($this->config["middleware"]["middleware"] as $singleGlobalMiddlewareRowKey => $singleGlobalMiddlewareRowValue) {
                
                 //echo "$singleGlobalMiddlewareRowKey: " . $singleGlobalMiddlewareRowKey . "\n";
                //echo "$singleGlobalMiddlewareRowValue: " . $singleGlobalMiddlewareRowValue . "\n";
                
                $this->constructedResponse[] = new $singleGlobalMiddlewareRowValue();
                //$this->middlewarePipeQueue->pipe(new $singleGlobalMiddlewareRowValue());
                
            }
            
            foreach ($this->config["middleware"]["middlewareGroups"] as $singleGlobalMiddlewareRowKey => $singleGlobalMiddlewareRowValue) {
                echo "enter";
                if($singleGlobalMiddlewareRowKey == "web"){
                     echo "enter1";
                   foreach($singleGlobalMiddlewareRowValue as $x){
                       
                        $this->constructedResponse[] = new $x();
                   } 
                }
                 if($singleGlobalMiddlewareRowKey == "api"){
                     echo "enter2";
                   foreach($singleGlobalMiddlewareRowValue as $x){
                       if(is_string($x)){
                           echo "<br>" . "yes i am string" . "<br>" ;
                           $mystring = $x;
                            $findme   = ':';
                            $pos = strpos($mystring, $findme);
                           if ($pos === false) {
                                echo "The string '$findme' was not found in the string '$mystring'";
                            } else {
                                echo "The string '$findme' was found in the string '$mystring'";
                                echo " and exists at position $pos";
                                $abc = explode($findme, $mystring);
                               
                                 foreach($this->config["middleware"]["routeMiddleware"] as $singlerouteMiddlewareKey => $singlerouteMiddlewareValue){
                                     echo "    in route middle ware" . $mystring;
                                    if($abc[0] == $singlerouteMiddlewareKey){
                                       echo "I am here!!!!!!!!!!!!!" . $singlerouteMiddlewareValue; 
                                        $this->constructedResponse[] = new $singlerouteMiddlewareValue();
                                    }
                                                                          
                                 }
                            }
                       }
                        //$this->constructedResponse[] = new $x();
                   } 
                }
                
                 //echo "$singleGlobalMiddlewareRowKey: " . $singleGlobalMiddlewareRowKey . "\n";
                //echo "$singleGlobalMiddlewareRowValue: " . $singleGlobalMiddlewareRowValue . "\n";
                
               // $this->constructedResponse[$singleGlobalMiddlewareRowKey] = new $singleGlobalMiddlewareRowValue();
                //$this->middlewarePipeQueue->pipe(new $singleGlobalMiddlewareRowValue());
                
            }
            
            
            var_dump($this->constructedResponse);
            
            
            /*echo "before adding middleware into pipe<br>";
            var_dump($this->middlewarePipeQueue);
            
            $this->middlewarePipeQueue->pipe($this->constructedResponse);
            
            echo "after adding middleware into pipe<br>";
            var_dump($this->middlewarePipeQueue);*/
            
        }
        
            
        
    }
    
}
