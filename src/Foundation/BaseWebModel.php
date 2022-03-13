<?php
namespace EaseAppPHP\Foundation;

use \EaseAppPHP\Foundation\Interfaces\ArrayableInterface;

use \Illuminate\Container\Container;

if ((interface_exists('\ArrayAccess')) && (interface_exists('\JsonSerializable')) && (interface_exists('\Countable')) && (interface_exists('\EaseAppPHP\Foundation\Interfaces\ArrayableInterface'))) {
	
    class BaseWebModel implements \ArrayAccess, \JsonSerializable, \Countable, ArrayableInterface
    {
		protected $container;
		protected $config;
		protected $matchedRouteDetails;
		protected $queryParams;
		protected $processedModelResponse;

		public function __construct(Container $container, $config, $matchedRouteDetails, $queryParams)
		{
			$this->container = $container;
			$this->config = $config;
			$this->matchedRouteDetails = $matchedRouteDetails;
			$this->queryParams = $queryParams;
			$this->processedModelResponse = new \stdClass();
		}

        public function offsetExists ($offset)
		{
			return isset($this->container[$offset]);
		}

		public function offsetGet ($offset)
		{
			return isset($this->container[$offset]) ? $this->container[$offset] : null;
		}

		public function offsetSet ($offset, $value)
		{
			if (is_null($offset)) {
				
				$this->container[] = $value;
			  
			} else {
				
				$this->container[$offset] = $value;
			
			}
		}

		public function offsetUnset ($offset)
		{
			unset($this->container[$offset]);
		}
		
		/**
		 * Do JSON Serialize based upon JsonSerializable interface
		 *
		 * @return array
		 */
		public function jsonSerialize(){
			return [];
		}
		
		/**
		 * Get the instance as an array.
		 *
		 * @return array
		 */
		public function toArray(){
			return [];
		}
		
		public function count() : int {
			return count($this->container);
		}
		
		/**
		 * Get Route related Template Context
		 * 
		 * @return string
		 */
		public function getRouteRelTemplateContext()
		{
			//Web Applications: This does the loading of the Modal Aspect (logic with db interaction) respective resource for regular web application requests. 
			//Values include: (frontend-web-app | backend-web-app | web-app-common). Note: $config["route_rel_template_context"] will have to be defined in model file, for routes with route-type = web-app-common.
			//$this->getRouteRelTemplateFolderPathPrefix();
			
			if ($this->matchedRouteDetails["route_type"] == "frontend-web-app") {
				
				$routeRelTemplateContext = "frontend";			
				
			} elseif ($this->matchedRouteDetails["route_type"] == "backend-web-app") {
				
				$routeRelTemplateContext = "backend";
				
			} else {
				
				$routeRelTemplateContext = "";
				
			}
			
			return $routeRelTemplateContext;			
		}
		
		/**
		 * Get Route related Template Folder Path Prefix
		 * 
		 * @return string
		 */
		public function getRouteRelTemplateFolderPathPrefix()
		{
			//Web Applications: This does the loading of the Modal Aspect (logic with db interaction) respective resource for regular web application requests. 
			//Values include: (frontend-web-app | backend-web-app | web-app-common). Note: $config["route_rel_template_context"] will have to be defined in model file, for routes with route-type = web-app-common.
			
			if ($this->matchedRouteDetails["route_type"] == "frontend-web-app") {
				
				$routeRelTemplateFolderPathPrefix = $this->config["mainconfig"]["route_rel_templates_folder_path_prefix"] . '/' . $this->config["mainconfig"]["chosen_frontend_template"];			
				
			} elseif ($this->matchedRouteDetails["route_type"] == "backend-web-app") {
				
				$routeRelTemplateFolderPathPrefix = $this->config["mainconfig"]["route_rel_templates_folder_path_prefix"] . '/' . $this->config["mainconfig"]["chosen_template"];
				
			} else {
				
				$routeRelTemplateFolderPathPrefix = "";
				
			}
			
			return $routeRelTemplateFolderPathPrefix;	
		}
		
		public static function renderHtml($viewPageFileName)
		{
			ob_start();
			require htmlspecialchars($viewPageFileName, ENT_QUOTES);
			return ob_get_clean();
		}
	
	}
	
}