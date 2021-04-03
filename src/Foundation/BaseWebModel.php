<?php
namespace EaseAppPHP\Foundation;


if ((interface_exists('ArrayAccess')) && (interface_exists('Arrayable')) && (interface_exists('JsonSerializable'))) {
    class BaseWebModel implements ArrayAccess, Arrayable, JsonSerializable
    {

        protected $container;

        /**
         * The middleware registered on the controller.
         *
         * @var array
         */
        protected $middleware = [];


        /**
         * Get the middleware assigned to the controller.
         *
         * @return array
         */
        public function getMiddleware()
        {
            return $this->middleware;
        }

    }
}

