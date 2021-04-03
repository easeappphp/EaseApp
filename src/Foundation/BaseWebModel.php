<?php
namespace EaseAppPHP\Foundation;

use \EaseAppPHP\Foundation\Interfaces\ArrayableInterface;


if ((interface_exists('\ArrayAccess')) && (interface_exists('\JsonSerializable')) && (interface_exists('\Countable')) && (interface_exists('\EaseAppPHP\Foundation\Interfaces\ArrayableInterface'))) {
    class BaseWebModel implements \ArrayAccess, \JsonSerializable, \Countable, ArrayableInterface
    {

        protected $container;

        public function offsetExists ($offset) {

			return isset($this->container[$offset]);

		}

		public function offsetGet ($offset) {

			return isset($this->container[$offset]) ? $this->container[$offset] : null;

		}

		public function offsetSet ($offset, $value) {

			if (is_null($offset)) {
			  $this->container[] = $value;
			} else {
			  $this->container[$offset] = $value;
			}

		}

		public function offsetUnset ($offset) {

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
	
	}
}

