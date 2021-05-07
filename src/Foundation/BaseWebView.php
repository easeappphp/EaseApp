<?php
namespace EaseAppPHP\Foundation;


if (interface_exists('\EaseAppPHP\Foundation\Interfaces\BaseWebViewInterface')) {
    class BaseWebView implements \EaseAppPHP\Foundation\Interfaces\BaseWebViewInterface
    {

        protected $container;
		protected $viewPageFileName;
		protected $dataObject;

        public function __construct($viewPageFileName, $dataObject)
		{
			
			$this->viewPageFileName = $viewPageFileName;
			$this->dataObject = $dataObject;
			
		}


        /**
		 * Render the View
		 *
		 * @return array
		 */
		public function render()
		{
			
			extract(get_object_vars($this->dataObject), EXTR_SKIP);
			
			ob_start();
			require $this->viewPageFileName;
			return ob_get_clean();
			
		}
		
    }
}

