<?php
namespace EaseAppPHP\Foundation;


class BaseWebController implements \EaseAppPHP\Foundation\Interfaces\BaseWebControllerInterface
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

    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        return call_user_func_array([$this, $method], $parameters);
        /*$handler = array( 'MyClass', 'MyMethod');
        $params = array(1,2,3,4);

        if (is_callable($handler)) { 
            call_user_func_array($handler , $params);
        }*/
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }

    
}