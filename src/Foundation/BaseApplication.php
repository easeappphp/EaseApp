<?php
namespace EaseAppPHP\Foundation;

//class Application extends Container implements ApplicationContract, CachesConfiguration, CachesRoutes, HttpKernelInterface
class BaseApplication implements \EaseAppPHP\Foundation\Interfaces\ApplicationInterface
{
    /**
     * The EaseApp PHP Framework version.
     *
     * @var string
     */
    const VERSION = '0.0.1';

    /**
     * The base path for the EaseApp PHP Framework installation.
     *
     * @var string
     */
    protected $basePath;

    /**
     * Indicates if the application has been bootstrapped before.
     *
     * @var bool
     */
    protected $hasBeenBootstrapped = false;

    /**
     * Indicates if the application has "booted".
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * The array of booting callbacks.
     *
     * @var callable[]
     */
    protected $bootingCallbacks = [];

    /**
     * The array of booted callbacks.
     *
     * @var callable[]
     */
    protected $bootedCallbacks = [];

    /**
     * The array of terminating callbacks.
     *
     * @var callable[]
     */
    protected $terminatingCallbacks = [];

    /**
     * All of the registered service providers.
     *
     * @var \Illuminate\Support\ServiceProvider[]
     */
    protected $serviceProviders = [];

    /**
     * The names of the loaded service providers.
     *
     * @var array
     */
    protected $loadedProviders = [];

    /**
     * The deferred services and their providers.
     *
     * @var array
     */
    protected $deferredServices = [];

    /**
     * The custom application path defined by the developer.
     *
     * @var string
     */
    protected $appPath;

    /**
     * The custom database path defined by the developer.
     *
     * @var string
     */
    protected $databasePath;

    /**
     * The custom storage path defined by the developer.
     *
     * @var string
     */
    protected $storagePath;

    /**
     * The custom environment path defined by the developer.
     *
     * @var string
     */
    protected $environmentPath;

    /**
     * The environment file to load during bootstrapping.
     *
     * @var string
     */
    protected $environmentFile = '.env';

    /**
     * Indicates if the application is running in the console.
     *
     * @var bool|null
     */
    protected $isRunningInConsole;

    /**
     * The application namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * The prefixes of absolute cache paths for use during normalization.
     *
     * @var array
     */
    protected $absoluteCachePathPrefixes = ['/', '\\'];

    /**
     * Create a new Illuminate application instance.
     *
     * @param  string|null  $basePath
     * @return void
     */
    /*public function __construct($basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

       // $this->registerBaseBindings();
        //$this->registerBaseServiceProviders();
        //$this->registerCoreContainerAliases();
    }*/
    
    /**
     * Set the base path for the application.
     *
     * @param  string  $basePath
     * @return $this
     */
    /*public function setBasePath($basePath)
    {
        
    }*/

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version()
    {
        return static::VERSION;
    }
    
    /**
    * Get the config array of the application.
    *
    * @return string
    */
    public function getConfig()
    {
       
    }
    
    /**
    * Get the Routing Engine User Rules/Routes array of the application.
    *
    * @return string
    */
    public function getRoutes()
    {
       
    }
    
    /**
    * Get Server Request of the application.
    *
    * @return string
    */
    public function getServerRequest()
    {
       
    }

    
}