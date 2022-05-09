<?php
declare(strict_types=1);

namespace EaseAppPHP\Core;

use \Illuminate\Container\Container;

/**
 * EAConfig Class
 *
 */
 
class EAConfig
{
	protected $container;
	protected $serverRequest;
	public $config = array();
	public $singleFileNameExploded = array();
	public $singleConfigItemArray = array();
	public $singleConfigItemString = "";
	public $singleConfigItemNull = null;
	public $dotSeparatedKeyBasedConfigArray = array();
	
	public function __construct(Container $container)
	{
		$this->container = $container;
		$this->serverRequest = $this->container->get('\Laminas\Diactoros\ServerRequestFactory');
	}
	
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
	public function getFromSingleFile(string $filePath)
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
	public function getFromSingleFolder(string $folderPath)
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
	public function getFromFilepathsArray(array $filepathsArray)
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
	 * Get the Config Array Data
	 *
	 * @param  array  $filepathsArray
	 * @return array
	 */
	public function get(string $configSource, string $configSourceValueDataType, string $configSourceValueData)
	{
		
		if (($configSource == 'As-Array') && ($configSourceValueDataType == 'array') && (is_array($configSourceValueData))) {

			$collectedConfigData = $this->getAsArray($configSourceValueData);

		} elseif (($configSource == 'From-Single-File') && ($configSourceValueDataType == 'string') && (is_string($configSourceValueData))) {

			$collectedConfigData = $this->getFromSingleFile($configSourceValueData);

		} elseif (($configSource == 'From-Single-Folder') && ($configSourceValueDataType == 'string') && (is_string($configSourceValueData))) {

			$collectedConfigData = $this->getFromSingleFolder($configSourceValueData);

		} elseif (($configSource == 'From-Filepaths-Array') && ($configSourceValueDataType == 'array') && (is_array($configSourceValueData))) {

			$collectedConfigData = $this->getFromFilepathsArray($configSourceValueData);

		} 
		
		return $collectedConfigData;
	}
	
	/**
	 * Generate dot seperated keys based config array, from the multi-dimensional config array.
	 *
	 * @param  array  $multiDimensionalConfigArray
	 * @param  string $prefix - an optional input parameter as prefix to all generated keys
	 * @return array
	 */
	public function generateDotSeparatedKeyBasedConfigArray(array $multiDimensionalConfigArray, $prefix = '')
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
	public function getDotSeparatedKeyValue(string $dotSeperatedConfigItem)
	{
		
		if (isset($this->dotSeparatedKeyBasedConfigArray[$dotSeperatedConfigItem])) {
			
			return $this->dotSeparatedKeyBasedConfigArray[$dotSeperatedConfigItem];
			
		} else {
			return null;
		}
		
	}
			
}