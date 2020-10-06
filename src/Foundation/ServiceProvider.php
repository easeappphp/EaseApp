<?php
namespace EaseAppPHP\Foundation;


//class Application extends Container implements ApplicationContract, CachesConfiguration, CachesRoutes, HttpKernelInterface
class ServiceProvider implements \EaseAppPHP\Foundation\Interfaces\ServiceProviderInterface
{
    
    protected $container;
    
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
       
    }
    
    /**
     * Boots any application services.
     *
     * @return void
     */
    public function boot()
    {
        
    }

    
}