<?php
declare(strict_types=1);

namespace EaseAppPHP\Providers;

use \EaseAppPHP\Foundation\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $container;
    
    protected $serverRequest;
    
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
            
            //1. Create a Server Request using Laminas\Diactoros PSR-7 Library
            // Returns new ServerRequest instance, using values from superglobals:
            $serverRequestInstance = \Laminas\Diactoros\ServerRequestFactory::fromGlobals();

            //Bind an existing "serverRequest" class instance to the container, by defining the Class Name as instance reference in the container
            $this->container->instance('\Laminas\Diactoros\ServerRequestFactory', $serverRequestInstance);

           //echo "app registered";
        
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
            
            $this->serverRequest = $this->container->get('\Laminas\Diactoros\ServerRequestFactory');
        
        }
    }
}
