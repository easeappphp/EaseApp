<?php
namespace EaseAppPHP\Foundation\Interfaces;

use Closure;
use Illuminate\Contracts\Container\Container;

interface BaseWebViewInterface
{
    
    
    /**
     * Render the View
     *
     * @return array
     */
    public function render();
	
}