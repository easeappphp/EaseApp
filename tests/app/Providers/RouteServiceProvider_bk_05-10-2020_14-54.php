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
    protected $routesList;
    protected $routes;
    protected $matchedRouteResponse;
    private $middlewarePipeQueue;
    protected $middlewarePipeQueueEntries;
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
            $this->container->instance('routes', $this->routes);
            $this->routeslist = $this->container->get('routes');
            //var_dump($this->routeslist);

            //Match Route			
            $this->matchedRouteResponse = $this->eaRouterinstance->matchRoute($this->routes, $this->serverRequest->getUri()->getPath(), $this->serverRequest->getQueryParams(), $this->serverRequest->getMethod(), $this->config["first-config"]["routing_rule_length"]);
            //var_dump($this->matchedRouteResponse);
            $this->container->instance('matchedRouteResponse', $this->matchedRouteResponse);
            //$this->routeslist = $this->container->get('matchedRouteResponse');
                        
            /*echo "<pre>";
            print_r($this->container->get('matchedRouteResponse'));
            echo "<br>";
            print_r($this->routeslist);
            */
            $required_matched_page_filename = $this->container->get('matchedRouteResponse')["matched_page_filename"];
            $required_route_type = "";
            foreach($this->routeslist as $key => $value){
                if($key ==  $required_matched_page_filename){
                    //print_r($value);
                    $required_route_type = $value["route_type"];
                    break;
                }
            }
           // echo "<br>";
           // var_dump($this->config["first-config"]["route_type_middleware_group_mapping"]);

            if($required_route_type != "" && array_key_exists($required_route_type, $this->config["first-config"]["route_type_middleware_group_mapping"])){
                $required_route_type_middleware_group_mapping_value = $this->config["first-config"]["route_type_middleware_group_mapping"][$required_route_type];
            }
            // Step 1: Do something first
            $appClassData = [
                    'config' => $this->config,
                    'routes' => $this->routes,
                    'eaRouterinstance' => $this->eaRouterinstance,
                    'matchedRouteResponse' => $this->matchedRouteResponse,
                    //'httpKernel' => $this->kernelInstance,
            ];
            
            //Define Laminas Stratigility Middlewarepipe
            $middlewarePipe = new \Laminas\Stratigility\MiddlewarePipe();  // API middleware collection
            $this->container->instance('\Laminas\Stratigility\MiddlewarePipe', $middlewarePipe);
            $this->middlewarePipeQueue = $this->container->get('\Laminas\Stratigility\MiddlewarePipe');
            //var_dump($this->middlewarePipeQueue);
            
            //Default Whoops based Error Handler using Whoops Middleware
            $this->middlewarePipeQueue->pipe(new \Franzl\Middleware\Whoops\WhoopsMiddleware);
            
            //run EaseAppPHPApplication\app\Http\Middleware\PassingAppClassDataToMiddleware
            //Middleware is expected to pass on the details as attributes of serverRequest to the next middleware
            $this->middlewarePipeQueue->pipe(new \EaseAppPHP\Http\Middleware\PassingAppClassDataToMiddleware($appClassData));
            

            //echo "<pre>";
           //var_dump($this->config["middleware"]["middleware"]);
            
            foreach ($this->config["middleware"]["middleware"] as $singleGlobalMiddlewareRowKey => $singleGlobalMiddlewareRowValue) {
                
                 //echo "$singleGlobalMiddlewareRowKey: " . $singleGlobalMiddlewareRowKey . "\n";
                //echo "$singleGlobalMiddlewareRowValue: " . $singleGlobalMiddlewareRowValue . "\n";
                
                //$this->constructedResponse[] = new $singleGlobalMiddlewareRowValue();
                $this->middlewarePipeQueue->pipe(new $singleGlobalMiddlewareRowValue());
                
            }
            
            /*foreach ($this->config["middleware"]["middlewareGroups"] as $singleMiddlewareGroupRowKey => $singleMiddlewareGroupRowValue) {
                //echo "enter";
                if($singleMiddlewareGroupRowKey == "web"){
                    // echo "enter1";
                    foreach($singleMiddlewareGroupRowValue as $singleMiddlewareGroupRowValueEntry){
                       
                        //$this->constructedResponse[] = new $singleMiddlewareGroupRowValueEntry();
                       $this->middlewarePipeQueue->pipe(new $singleMiddlewareGroupRowValueEntry());
                    } 
                }
                if($singleMiddlewareGroupRowKey == "api"){
                    //echo "enter2";
                    foreach($singleMiddlewareGroupRowValue as $singleMiddlewareGroupRowValueEntry){
                        if(is_string($singleMiddlewareGroupRowValueEntry)){
                            // echo "<br>" . "yes i am string" . "<br>" ;
                            $mystring = $singleMiddlewareGroupRowValueEntry;
                            $findme   = ':';
                            $pos = strpos($mystring, $findme);
                            if ($pos === false) {
                               // echo "The string '$findme' was not found in the string '$mystring'";
                            } else {
                                //  echo "The string '$findme' was found in the string '$mystring'";
                                // echo " and exists at position $pos";
                                $abc = explode($findme, $mystring);
                               
                                foreach($this->config["middleware"]["routeMiddleware"] as $singlerouteMiddlewareKey => $singlerouteMiddlewareValue){
                                    //     echo "    in route middle ware" . $mystring;
                                    if($abc[0] == $singlerouteMiddlewareKey){
                                        //     echo "I am here!!!!!!!!!!!!!" . $singlerouteMiddlewareValue; 
                                        //$this->constructedResponse[] = new $singlerouteMiddlewareValue();
                                        $this->middlewarePipeQueue->pipe(new $singlerouteMiddlewareValue());
                                    }
                                                                          
                                }
                            }
                        }
                        //$this->constructedResponse[] = new $x();
                    } 
                }
              
            }*/
            
            foreach ($this->config["middleware"]["middlewareGroups"] as $singleMiddlewareGroupRowKey => $singleMiddlewareGroupRowValue) {
                
                $expectedMiddlewareGroupsList = array("web", "api", "ajax");
                if (($required_route_type_middleware_group_mapping_value == $singleMiddlewareGroupRowKey) && (in_array($singleMiddlewareGroupRowKey, $expectedMiddlewareGroupsList))) {
                    
                    foreach($singleMiddlewareGroupRowValue as $singleMiddlewareGroupRowValueEntry){
                        if(is_string($singleMiddlewareGroupRowValueEntry)){
                            
                            $mystring = $singleMiddlewareGroupRowValueEntry;
                            $findme   = ':';
                            $pos = strpos($mystring, $findme);
                            if (!$pos === false) {
                                $abc = explode($findme, $mystring);
                               
                                foreach($this->config["middleware"]["routeMiddleware"] as $singlerouteMiddlewareKey => $singlerouteMiddlewareValue){
                                   
                                    if($abc[0] == $singlerouteMiddlewareKey){
                                         $this->middlewarePipeQueue->pipe(new $singlerouteMiddlewareValue());
                                    }
                                                                          
                                }
                            }
                        } else {
                            foreach($singleMiddlewareGroupRowValue as $singleMiddlewareGroupRowValueEntry){
                       
                               $this->middlewarePipeQueue->pipe(new $singleMiddlewareGroupRowValueEntry());
                            }
                        }
                        
                    }
                    break;
                }
              
            }
            
            /*
             * FEATURES POSTPONED w.r.t. IMPLEMENTATION middleware priority, adding/removing specific middleware to/from a ROUTE, postponing these two features andi for now
             * skip middleware parameters as we tried using attributes concept of server request. need to load list of middleware that i sloaded and checks to be made when loading other middleware to prevent duplication.
             * Before and after middleware logic along with terminable middleware logic to be considered for middleware implementation
             */
            
            //var_dump($this->middlewarePipeQueue);
            
            //Laminas 404 Not Found Handler. The namespace to be changed later to Laminas\Stratigility\Handler\NotFoundHandler
            $this->middlewarePipeQueue->pipe(new \Laminas\Stratigility\Middleware\NotFoundHandler(function () {
				return new \Laminas\Diactoros\Response();
			}));
            
           //Assign MiddlewarePipe entries into container
            $this->container->instance('middlewarePipeQueueEntries', $this->middlewarePipeQueue);
            /*$this->middlewarePipeQueueEntries = $this->container->get('middlewarePipeQueueEntries');
            
            echo "<pre>";
            print_r($this->middlewarePipeQueueEntries);*/
            
        }
        
            
        
    }
    
}
