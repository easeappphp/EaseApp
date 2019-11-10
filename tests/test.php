<?php 
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Config\Config;

$config_array = array("key"=>"123");

$config = new Config();
print_r($config->getInputAsArray($config_array));