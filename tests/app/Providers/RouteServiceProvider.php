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



            //WORKING var_dump($this->container->get('config')["mainconfig"]["routing_engine_rule_files"]);
            //TO TRY var_dump(getDataFromContainer('config')["mainconfig"]["routing_engine_rule_files"]);

            //Get Routes from /routes folder w.r.t. web, ajax, ajax-api-common, rest-api, soap-api related files. This scenario excludes CLI and Channels primarily.
            $this->routes = $this->eaRouterinstance->getFromFilepathsArray($this->config["mainconfig"]["routing_engine_rule_files"]);
            //var_dump($this->routes);
            $this->container->instance('routes', $this->routes);
            $this->routeslist = $this->container->get('routes');
            //var_dump($this->routeslist);

            //Match Route			
            $this->matchedRouteResponse = $this->eaRouterinstance->matchRoute($this->routes, $this->serverRequest->getUri()->getPath(), $this->serverRequest->getQueryParams(), $this->serverRequest->getMethod(), $this->config["mainconfig"]["routing_rule_length"]);
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
                    $required_with_middleware = $value["with_middleware"];
                    $required_without_middleware = $value["without_middleware"];
                    if($required_with_middleware != ""){
                        $required_with_middleware_array = explode(",", $required_with_middleware);
                    }
                    if($required_without_middleware != ""){
                        $required_without_middleware_array = explode(",", $required_without_middleware);
                    }
                    break;
                }
            }
            // echo "<br>";
            // var_dump($this->config["mainconfig"]["route_type_middleware_group_mapping"]);

            if($required_route_type != "" && array_key_exists($required_route_type, $this->config["mainconfig"]["route_type_middleware_group_mapping"])){
                $required_route_type_middleware_group_mapping_value = $this->config["mainconfig"]["route_type_middleware_group_mapping"][$required_route_type];
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
                $this->constructedResponse[] = $singleGlobalMiddlewareRowValue;
                //$this->middlewarePipeQueue->pipe(new $singleGlobalMiddlewareRowValue());
                
            }
            
            //echo "<pre>";
            //echo "constructed response: <br>";
            //print_r($this->constructedResponse);
            
            foreach ($this->config["middleware"]["middlewareGroups"] as $singleMiddlewareGroupRowKey => $singleMiddlewareGroupRowValue) {
                //echo "required_route_type_middleware_group_mapping_value: " . $required_route_type_middleware_group_mapping_value . "<br>";
                $expectedMiddlewareGroupsList = array("web", "api", "ajax");
                if (($required_route_type_middleware_group_mapping_value == $singleMiddlewareGroupRowKey) && (in_array($singleMiddlewareGroupRowKey, $expectedMiddlewareGroupsList))) {
                    //echo "enter<br>";
                    foreach($singleMiddlewareGroupRowValue as $singleMiddlewareGroupRowValueEntry){
                        //echo "enter1<br>";
                        //var_dump($singleMiddlewareGroupRowValueEntry);
                        
                        $pos = strpos($singleMiddlewareGroupRowValueEntry, ':');
                        if (!$pos === false) {
                        //if(is_string($singleMiddlewareGroupRowValueEntry)){
                            //echo "enter2<br>";
                            
                            //echo "enter 3<br>";
                            $abc = explode(':', $singleMiddlewareGroupRowValueEntry);

                            foreach($this->config["middleware"]["routeMiddleware"] as $singlerouteMiddlewareKey => $singlerouteMiddlewareValue){

                                if($abc[0] == $singlerouteMiddlewareKey){
                                    if(!isset($this->constructedResponse[$singlerouteMiddlewareKey])){
                                        $this->constructedResponse[] = $singlerouteMiddlewareValue;
                                    }
                                    //$this->middlewarePipeQueue->pipe(new $singlerouteMiddlewareValue());
                                }

                            }
                            
                        } else {
                            //echo "enter else 2<br>";
                            foreach($singleMiddlewareGroupRowValue as $singleMiddlewareGroupRowValueEntry){
                               //echo "enter else foreach 2<br>";
                                if(!in_array($singleMiddlewareGroupRowValueEntry, $this->constructedResponse)){
                                    $this->constructedResponse[] = $singleMiddlewareGroupRowValueEntry;
                                }
                               //$this->middlewarePipeQueue->pipe(new $singleMiddlewareGroupRowValueEntry());
                            }
                        }
                        
                    }
                    break;
                }
              
            }
            
            //echo "<pre>";
            //echo "constructed response TOTAL: <br>";
            //print_r($this->constructedResponse);
            if(isset($required_with_middleware_array)){
                foreach($required_with_middleware_array as $required_with_middleware_array_entry){
                    //echo "required_with_middleware_array_entry: " . $required_with_middleware_array_entry . "<br>";
                    foreach($this->config["middleware"]["routeMiddleware"] as $singlerouteMiddlewareKey => $singlerouteMiddlewareValue){

                        if($required_with_middleware_array_entry == $singlerouteMiddlewareKey){
                            
                            if(!isset($this->constructedResponse[$required_with_middleware_array_entry])){
                                $this->constructedResponse[] = $singlerouteMiddlewareValue;
                            }
                            
                        }

                    }
                }
            }
            if(isset($required_without_middleware_array)){
                foreach($required_without_middleware_array as $required_without_middleware_array_entry){
                    foreach($this->config["middleware"]["routeMiddleware"] as $singlerouteMiddlewareKey => $singlerouteMiddlewareValue){

                        if($required_without_middleware_array_entry == $singlerouteMiddlewareKey){
                            
                            if(isset($this->constructedResponse[$required_without_middleware_array_entry])){
                                unset($this->constructedResponse[$required_without_middleware_array_entry]);
                                echo "middleware removed";
                                
                                //ISSUE TO BE FIXED
                            }
                            
                        }

                    }
                    
                }
            }
            
            
            foreach ($this->constructedResponse as $constructedResponseRowKey => $constructedResponseRowValue) {
                //echo $constructedResponseRowValue . "<br>";
                $this->middlewarePipeQueue->pipe(new $constructedResponseRowValue());
                
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
