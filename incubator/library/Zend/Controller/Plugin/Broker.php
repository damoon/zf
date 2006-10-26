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
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


/** Zend_Controller_Plugin_Abstract */
require_once 'Zend/Controller/Plugin/Abstract.php';

/** Zend_Controller_Dispatcher_Token */
require_once 'Zend/Controller/Dispatcher/Token.php';


/**
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Controller_Plugin_Broker extends Zend_Controller_Plugin_Abstract
{

    /**
     * Array of instance of objects extending Zend_Controller_Plugin_Abstract
     *
     * @var array
     */
    protected $_plugins = array();


    /**
     * Register a plugin.
     *
     * @param Zend_Controller_Plugin_Abstract $plugin
     * @return Zend_Controller_Plugin_Broker
     */
    public function registerPlugin(Zend_Controller_Plugin_Abstract $plugin)
    {
        if (false !== array_search($plugin, $this->_plugins, true)) {
            throw new Zend_Controller_Exception('Plugin already registered.');
        }
        $this->_plugins[] = $plugin;
        return $this;
    }


    /**
     * Unregister a plugin.
     *
     * @param Zend_Controller_Plugin_Abstract $plugin
     * @return Zend_Controller_Plugin_Broker
     */
    public function unregisterPlugin(Zend_Controller_Plugin_Abstract $plugin)
    {
        $key = array_search($plugin, $this->_plugins, true);
        if (false === $key) {
            throw new Zend_Controller_Exception('Plugin never registered.');
        }
        unset($this->_plugins[$key]);
        return $this;
    }


	/**
	 * Called before Zend_Controller_Front begins evaluating the
	 * request against its routes.
	 *
	 * @return void
	 */
	public function routeStartup()
	{
	    foreach ($this->_plugins as $plugin) {
	        $plugin->routeStartup();
	    }
	}


	/**
	 * Called before Zend_Controller_Front exits its iterations over
	 * the route set.
	 *
	 * @param  Zend_Controller_Request_Abstract $request
	 * @return void
	 */
	public function routeShutdown($request)
	{
	    foreach ($this->_plugins as $plugin) {
	        $plugin->routeShutdown($request);
	    }
	}


	/**
	 * Called before Zend_Controller_Front enters its dispatch loop.
     *
     * During the dispatch loop, Zend_Controller_Front keeps a
     * Zend_Controller_Request_Abstract object, and uses
     * Zend_Controller_Dispatcher to dispatch the
     * Zend_Controller_Request_Abstract object to controllers/actions.
	 *
	 * @param  Zend_Controller_Request_Abstract $request
	 * @return void
	 */
	public function dispatchLoopStartup($request)
	{
	    foreach ($this->_plugins as $plugin) {
	        $plugin->dispatchLoopStartup($request);
	    }
	}


	/**
	 * Called before an action is dispatched by Zend_Controller_Dispatcher.
	 *
	 * @param  Zend_Controller_Request_Abstract $request
	 * @return void
	 */
	public function preDispatch($request)
	{
	    foreach ($this->_plugins as $plugin) {
	        $plugin->preDispatch($request);
	    }
	}


	/**
	 * Called after an action is dispatched by Zend_Controller_Dispatcher.
	 *
	 * @param  Zend_Controller_Request_Abstract $request
	 * @return void
	 */
	public function postDispatch($request)
	{
	    foreach ($this->_plugins as $plugin) {
	        $plugin->postDispatch($request);
	    }
	}


	/**
	 * Called before Zend_Controller_Front exits its dispatch loop.
	 *
	 * @param  Zend_Controller_Request_Abstract $request
	 * @return void
	 */
	public function dispatchLoopShutdown()
	{
	   foreach ($this->_plugins as $plugin) {
	       $plugin->dispatchLoopShutdown();
	   }
	}
}
