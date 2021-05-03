<?php
namespace EaseAppPHP\Foundation;


if (interface_exists('\EaseAppPHP\Foundation\Interfaces\BaseWebViewInterface')) {
    class BaseWebView implements \EaseAppPHP\Foundation\Interfaces\BaseWebViewInterface
    {

        protected $filename;
		protected $dataArray;

        public function __construct($filename, $dataArray)
		{
			
			$this->filename = $filename;
			$this->dataArray = $dataArray;
			
		}


        /**
		 * Render the View
		 *
		 * @return array
		 */
		public function render()
		{
			echo "\n echoing render method \n";
			extract($this->dataArray);
			
			echo "name: " . $name . "\n";
			echo "place: " . $place . "\n";
			include($this->filename);
			
			
		}

    }
}

