<?php
namespace EaseAppPHP\Foundation\Interfaces;

use Closure;
use Illuminate\Contracts\Container\Container;

//interface ApplicationInterface extends Container
interface ApplicationInterface
{
    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version();

    /**
     * Get the base path of the Laravel installation.
     *
     * @return string
     */
    
}