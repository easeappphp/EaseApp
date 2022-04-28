<?php
declare(strict_types=1);

namespace EaseAppPHP\Other;

use \Illuminate\Container\Container;

use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;
use \Monolog\Handler\FirePHPHandler;

/**
 * EAConstants Class
 */
 
final class EAConstants
{
	// see https://tldp.org/LDP/abs/html/exitcodes.html
	public const CLI_RESPONSE_SUCCESS = 0;
	public const CLI_RESPONSE_FAILURE = 1;
	public const CLI_RESPONSE_INVALID = 2;
}