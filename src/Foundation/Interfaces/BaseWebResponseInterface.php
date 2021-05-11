<?php
namespace EaseAppPHP\Foundation\Interfaces;

use Closure;
use Illuminate\Contracts\Container\Container;

interface BaseWebResponseInterface
{
    
    
    /**
     * Set Content for the response as Text
     *
     * 
     */
    public function setText(string $content, int $httpStatusCode = 200);
	
	/**
     * Set Content for the response as HTML
     *
     * 
     */
    public function setHTML(string $content, int $httpStatusCode = 200);
	
	/**
     * Set Content for the response as XML
     *
     * 
     */
    public function setXML(string $content, int $httpStatusCode = 200);
	
	/**
     * Set Content for the response as JSON
     *
     * 
     */
    public function setJSON($content, int $httpStatusCode = 200, $headers = ['Content-Type' => ['application/json']], $flag = JSON_PRETTY_PRINT);
	
	/**
     * Set Content for the response as EMPTY
     *
     * 
     */
    public function setEmpty(int $httpStatusCode = 204, array $headers = []);
	
	/**
     * Set Redirect
     *
     * 
     */
    public function setRedirect();
	
}