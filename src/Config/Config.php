<?php
namespace Config;

/**
 * Config Class
 *
 */
 
class Config
{
	private $config = array();
	private $singleFileNameExploded = array();
	
	/**
	 * Accepts Extracted Config Array
	 *
	 * @param array $configArray
	 * @return array
	 */
	public function getInputAsArray($configArray = array())
	{
		$this->config = $configArray;
		
		return $this->config;
	}
			
}
?>