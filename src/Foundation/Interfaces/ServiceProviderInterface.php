<?php
namespace EaseAppPHP\Foundation\Interfaces;

use Closure;
use Illuminate\Contracts\Container\Container;

interface ServiceProviderInterface
{
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register();
    
    /**
     * Boots any application services.
     *
     * @return void
     */
    public function boot();
    
}