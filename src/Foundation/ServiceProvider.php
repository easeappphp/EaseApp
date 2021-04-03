<?php
namespace EaseAppPHP\Foundation;

if (interface_exists('\EaseAppPHP\Foundation\Interfaces\ServiceProviderInterface')) {
    class ServiceProvider implements \EaseAppPHP\Foundation\Interfaces\ServiceProviderInterface
    {

        protected $container;

        /**
         * Create a new Illuminate application instance.
         *
         * @param  object  $container
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
}

