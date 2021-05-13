<?php
namespace EaseAppPHP\Foundation\Interfaces;

use Closure;

interface BaseWebViewInterface
{
    
    
    /**
     * Render the View
     *
     * @return array
     */
    public static function render($viewPageFileName, $dataObject);
	
}