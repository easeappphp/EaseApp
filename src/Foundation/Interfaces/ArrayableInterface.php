<?php
namespace EaseAppPHP\Foundation\Interfaces;

use Closure;
use Illuminate\Contracts\Container\Container;

interface ArrayableInterface
{
    
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray();
    
}