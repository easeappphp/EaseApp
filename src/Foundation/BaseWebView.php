<?php
namespace EaseAppPHP\Foundation;

if (interface_exists('\EaseAppPHP\Foundation\Interfaces\BaseWebViewInterface')) {
	
    class BaseWebView implements \EaseAppPHP\Foundation\Interfaces\BaseWebViewInterface
    {
		protected $viewPageFileName;
		protected $dataObject;

        /**
		 * Render the View
		 *
		 */
		public static function render($viewPageFileName, $dataObject)
		{
			extract(get_object_vars($dataObject), EXTR_SKIP);
			
			ob_start();
			
			include htmlspecialchars($routeRelTemplateFolderPathPrefix, ENT_QUOTES) . "/header-top.php";
			include htmlspecialchars($routeRelTemplateFolderPathPrefix, ENT_QUOTES) . "/header.php";
			require htmlspecialchars($viewPageFileName, ENT_QUOTES);
			include htmlspecialchars($routeRelTemplateFolderPathPrefix, ENT_QUOTES) . "/footer.php";
			
			return ob_get_clean();
		}
	}
	
}

