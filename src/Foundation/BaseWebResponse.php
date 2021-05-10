<?php
namespace EaseAppPHP\Foundation;

use Illuminate\Container\Container;

if (interface_exists('\EaseAppPHP\Foundation\Interfaces\BaseWebResponseInterface')) {
    class BaseWebResponse implements \EaseAppPHP\Foundation\Interfaces\BaseWebResponseInterface
    {

        protected $container;
		protected $response;

        public function __construct($container)
		{
			
			$this->container = $container;
			
		}

        /**
		 * Set Content for the response as Text
		 *
		 * 
		 */
		public function setText(string $content, int $httpStatusCode = 200)
		{
			
			$this->response = new \Laminas\Diactoros\Response\TextResponse(
				$content,
				$httpStatusCode,
				['Content-Type' => ['text/plain']]
			);
			
			return $this->response;
			
		}
		
		/**
		 * Set Content for the response as HTML
		 *
		 * 
		 */
		public function setHTML(string $content, int $httpStatusCode = 200)
		{
			
			$this->response = new \Laminas\Diactoros\Response\HtmlResponse(
				$content,
				$httpStatusCode,
				['Content-Type' => ['application/xhtml+xml']]
			);
			
			return $this->response;
			
		}
		
		/**
		 * Set Content for the response as XML
		 *
		 * 
		 */
		public function setXML(string $content, int $httpStatusCode = 200)
		{
			
			$this->response = new \Laminas\Diactoros\Response\XmlResponse(
				$content,
				$httpStatusCode,
				['Content-Type' => ['application/hal+xml']]
			);
			
			return $this->response;
			
		}
		
		/**
		 * Set Content for the response as JSON
		 *
		 * 
		 */
		public function setJSON($content, int $httpStatusCode = 200)
		{
			
			if (is_object($content)) {
				
				$contentJsonEncoded = json_encode($content);
				
			} else {
				
				$contentJsonEncoded = json_encode($content, true);
				
			}
			$this->response = new \Laminas\Diactoros\Response\JsonResponse(
				$contentJsonEncoded,
				$httpStatusCode,
				['Content-Type' => ['application/json']]
			);
			
			return $this->response;
			
		}
		
		/**
		 * Set Content for the response as EMPTY
		 *
		 * 
		 */
		public function setEmpty(int $httpStatusCode = 204, array $headers = [])
		{
			
			$this->response = new \Laminas\Diactoros\Response\EmptyResponse($httpStatusCode, $headers);
			
			return $this->response;
			
		}
		
		/**
		 * Set Redirect
		 *
		 * 
		 */
		public function setRedirect()
		{
			
			
			
		}
		
    }
}

