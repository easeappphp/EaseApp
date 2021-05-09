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
    public function setText();
	
	/**
     * Set Content for the response as HTML
     *
     * 
     */
    public function setHTML();
	
	/**
     * Set Content for the response as XML
     *
     * 
     */
    public function setXML();
	
	/**
     * Set Content for the response as JSON
     *
     * 
     */
    public function setJSON();
	
	/**
     * Set Content for the response as EMPTY
     *
     * 
     */
    public function setEmpty();
	
	/**
     * Set Redirect
     *
     * 
     */
    public function setRedirect();
	
}