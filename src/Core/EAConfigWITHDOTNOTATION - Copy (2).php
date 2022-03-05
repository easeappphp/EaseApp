<?php
declare(strict_types=1);

namespace EaseAppPHP\Core;

/**
 * EAConfig Class
 *
 */
 
class EAConfig
{
	public $config = array();
	public $singleFileNameExploded = array();
	public $singleConfigItemArray = array();
	public $singleConfigItemString = "";
	public $singleConfigItemNull = null;
	public $dotSeparatedKeyBasedConfigArray = array();
	
	/**
	 * Accepts Extracted Config Array
	 *
	 * @param array $configArray
	 * @return array
	 */
	public function getAsArray($configArray = array())
	{
		$this->config = $configArray;
		
		return $this->config;
	}
	
	/**
	 * Gets the Config Array from a Single Config File
	 *
	 * @param string $filePath
	 * @return array
	 */
	public function getFromSingleFile($filePath)
	{
		//$config = require __DIR__.'/main-config.php';
		return require $filePath;
	}

	/**
	 * Gets the Config Array from Multiple Config Files, that resides in a Single Config Folder. This method will read only PHP Files with Config info in an array, and specifically does not read those Config files, that have spaces in the filename.
	 *
	 * @param string $folderPath
	 * @return array
	 */
	public function getFromSingleFolder($folderPath)
	{
		foreach (glob($folderPath . "/*.php") as $singleFilePath) {
			
			$this->singleFileNameExploded = explode(".", basename($singleFilePath));
			
			if (stripos($this->singleFileNameExploded[0], " ") === false) {
				
				$this->config[$this->singleFileNameExploded[0]] = require $singleFilePath;
				
			}
			
		}
		
		return $this->config;
	}

	/**
	 * Gets the Config Array from Multiple Config Files, which list is provided as a numeric index array. This method will read only PHP Files with Config info in an array, from given paths.
	 *
	 * @param  array  $filepathsArray
	 * @return array
	 */
	public function getFromFilepathsArray($filepathsArray)
	{
		foreach ($filepathsArray as $singleFilePath) {
			
			$this->singleFileNameExploded = explode(".", basename($singleFilePath));
			
			if (stripos($this->singleFileNameExploded[0], " ") === false) {
				
				$this->config[$this->singleFileNameExploded[0]] = require $singleFilePath;
				
			}
			
		}
		
		return $this->config;
	}
	
	/**
	 * Generate dot seperated keys based config array, from the multi-dimensional config array.
	 *
	 * @param  array  $multiDimensionalConfigArray
	 * @param  string $prefix - an optional input parameter as prefix to all generated keys
	 * @return array
	 */
	public function generateDotSeparatedKeyBasedConfigArray($multiDimensionalConfigArray, $prefix = '')
	{
		
		foreach ($multiDimensionalConfigArray as $key => $value) {
			
			if (is_array($value) && ! empty($value)) {
				$this->dotSeparatedKeyBasedConfigArray[$prefix.$key] = $value;
				$this->dotSeparatedKeyBasedConfigArray = array_merge($this->dotSeparatedKeyBasedConfigArray, $this->generateDotSeparatedKeyBasedConfigArray($value, $prefix.$key.'.'));
			} else {
				$this->dotSeparatedKeyBasedConfigArray[$prefix.$key] = $value;
			}
			
		}

		return $this->dotSeparatedKeyBasedConfigArray;
	}
	
	/**
	 * Gets the specific Config Array item from dot separated key based config array.
	 *
	 * @param  string  $dotSeperatedConfigItem
	 * @return mixed
	 */
	public function getDotSeparatedKeyValue($dotSeperatedConfigItem)
	{
		
		if (isset($this->dotSeparatedKeyBasedConfigArray[$dotSeperatedConfigItem])) {
			
			return $this->dotSeparatedKeyBasedConfigArray[$dotSeperatedConfigItem];
			
		} else {
			return "";
		}
		
	}
			
}