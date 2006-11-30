<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @package    Zend_Controller
 * @subpackage Router
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @version    $Id$
 * @license    http://www.zend.com/license/framework/1_0.txt Zend Framework License version 1.0
 */

/** Zend_Controller_Router_Interface */
require_once 'Zend/Controller/Router/Interface.php';

/** Zend_Controller_Request_Abstract */
require_once 'Zend/Controller/Request/Abstract.php';

/** Zend_Controller_Request_Http */
require_once 'Zend/Controller/Request/Http.php';

/** Zend_Controller_Route */
require_once 'Zend/Controller/Router/Route.php';

/**
 * Ruby routing based Router.
 *
 * @package    Zend_Controller
 * @subpackage Router
 * @copyright  Copyright (c) 2005-2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://www.zend.com/license/framework/1_0.txt Zend Framework License version 1.0
 * @see        http://manuals.rubyonrails.com/read/chapter/65
 */
class Zend_Controller_RewriteRouter implements Zend_Controller_Router_Interface
{

    /**
     * Array of invocation parameters to use when instantiating action 
     * controllers
     * @var array 
     */
    protected $_invokeParams = array();
    protected $_routes = array();
    protected $_currentRoute = null;

    /**
     * Constructor
     * 
     * @param array $params Optional invocation arguments
     * @return void
     */
    public function __construct(array $params = array())
    {
        $this->setParams($params);
        $this->addDefaultRoutes();
    }

    /**
     * Add or modify a parameter to use when instantiating an action controller
     * 
     * @param string $name 
     * @param mixed $value 
     * @return Zend_Controller_RewriteRouter
     */
    public function setParam($name, $value)
    {
        $name = (string) $name;
        $this->_invokeParams[$name] = $value;
        return $this;
    }

    /**
     * Set parameters to pass to action controller constructors
     * 
     * @param array $params 
     * @return Zend_Controller_RewriteRouter
     */
    public function setParams(array $params)
    {
        $this->_invokeParams = array_merge($this->_invokeParams, $params);
        return $this;
    }

    /**
     * Retrieve a single parameter from the controller parameter stack
     * 
     * @param string $name 
     * @return mixed
     */
    public function getParam($name)
    {
        if(isset($this->_invokeParams[$name])) {
            return $this->_invokeParams[$name];
        }

        return null;
    }

    /**
     * Retrieve action controller instantiation parameters
     * 
     * @return array
     */
    public function getParams()
    {
        return $this->_invokeParams;
    }

    /**
     * Clear the controller parameter stack
     *
     * By default, clears all parameters. If a parameter name is given, clears 
     * only that parameter; if an array of parameter names is provided, clears 
     * each.
     * 
     * @param null|string|array single key or array of keys for params to clear
     * @return Zend_Controller_RewriteRouter
     */
    public function clearParams($name = null)
    {
        if (null === $name) {
            $this->_invokeParams = array();
        } elseif (is_string($name) && isset($this->_invokeParams[$name])) {
            unset($this->_invokeParams[$name]);
        } elseif (is_array($name)) {
            foreach ($name as $key) {
                if (is_string($key) && isset($this->_invokeParams[$key])) {
                    unset($this->_invokeParams[$key]);
                }
            }
        }

        return $this;
    }

    /** 
     * Add default routes which are used to mimic basic router behaviour
     */
    protected function addDefaultRoutes()
    {
        $compat = new Zend_Controller_Router_Route(':controller/:action/*', array('controller' => 'index', 'action' => 'index'));
        $this->addRoute('default', $compat); 
    }

    /** 
     * Add route to the route chain
     * 
     * @param string Name of the route
     * @param Zend_Controller_Router_Route_Interface Route
     */
    public function addRoute($name, Zend_Controller_Router_Route_Interface $route) {
        $this->_routes[$name] = $route;
    }

    /** 
     * Add routes to the route chain
     * 
     * @param array Array of routes with names as keys and routes as values 
     */
    public function addRoutes($routes) {
        foreach ($routes as $name => $route) {
            $this->addRoute($name, $route);
        }
    }

    /** 
     * Create routes out of Zend_Config configuration
     * 
     * Example INI:
     * route.archive.route = "archive/:year/*"
     * route.archive.defaults.controller = archive
     * route.archive.defaults.action = show
     * route.archive.defaults.year = 2000
     * route.archive.reqs.year = "\d+"
     * 
     * And finally after you have created a Zend_Config with above ini:
     * $router = new Zend_Controller_RewriteRouter();
     * $router->addConfig($config, 'route');
     * 
     * @param Zend_Config Configuration object
     * @param string Name of the config section containing route's definitions  
     * @throws Zend_Controller_Router_Exception
     */
    public function addConfig(Zend_Config $config, $section) 
    {
        if ($config->{$section} === null) {
            throw Zend::exception('Zend_Controller_Router_Exception', "No route configuration in section '{$section}'");
        }
        foreach ($config->{$section} as $name => $info) {
            $reqs = (isset($info->reqs)) ? $info->reqs->asArray() : null;
            $defs = (isset($info->defaults)) ? $info->defaults->asArray() : null;
            $this->addRoute($name, new Zend_Controller_Router_Route($info->route, $defs, $reqs));
        }
    }

    /** 
     * Remove a route from the route chain
     * 
     * @param string Name of the route
     * @throws Zend_Controller_Router_Exception
     */
    public function removeRoute($name) {
        if (!isset($this->_routes[$name])) {
            throw Zend::exception('Zend_Controller_Router_Exception', "Route $name is not defined");
        }
        unset($this->_routes[$name]);
    }

    /** 
     * Remove all standard default routes
     * 
     * @param string Name of the route
     * @param Zend_Controller_Router_Route_Interface Route
     */
    public function removeDefaultRoutes() {
        $this->removeRoute('default');
    }

    /** 
     * Retrieve a named route 
     * 
     * @param string Name of the route
     * @throws Zend_Controller_Router_Exception
     * @return Zend_Controller_Router_Route_Interface Route object
     */
    public function getRoute($name)
    {
        if (!isset($this->_routes[$name])) {
            throw Zend::exception('Zend_Controller_Router_Exception', "Route $name is not defined");
        }
        return $this->_routes[$name];
    }

    /** 
     * Retrieve a currently matched route 
     * 
     * @throws Zend_Controller_Router_Exception
     * @return Zend_Controller_Router_Route_Interface Route object
     */
    public function getCurrentRoute()
    {
        if (!isset($this->_currentRoute)) {
            throw Zend::exception('Zend_Controller_Router_Exception', "Current route is not defined");
        }
        return $this->getRoute($this->_currentRoute);
    }

    /** 
     * Retrieve a name of currently matched route 
     * 
     * @throws Zend_Controller_Router_Exception
     * @return Zend_Controller_Router_Route_Interface Route object
     */
    public function getCurrentRouteName()
    {
        if (!isset($this->_currentRoute)) {
            throw Zend::exception('Zend_Controller_Router_Exception', "Current route is not defined");
        }
        return $this->_currentRoute;
    }

    /** 
     * Retrieve an array of routes added to the route chain 
     * 
     * @return array All of the defined routes
     */
    public function getRoutes()
    {
        return $this->_routes;
    }

    /** 
     * Find a matching route to the current PATH_INFO and inject 
     * returning values to the Request object. 
     * 
     * @throws Zend_Controller_Router_Exception 
     * @return Zend_Controller_Request_Abstract Request object
     */
    public function route(Zend_Controller_Request_Abstract $request)
    {
        
        if (!$request instanceof Zend_Controller_Request_Http) {
            throw Zend::exception('Zend_Controller_Router_Exception', 'Zend_Controller_RewriteRouter requires a Zend_Controller_Request_Http-based request object');
        }

        $pathInfo = $request->getPathInfo();

        /** Find the matching route */
        foreach (array_reverse($this->_routes) as $name => $route) {
            if ($params = $route->match($pathInfo)) {
                foreach ($params as $param => $value) {
                    $request->setParam($param, $value);
                }
                $this->_currentRoute = $name;
                break;
            }
        }
        
        return $request;

    }

}
