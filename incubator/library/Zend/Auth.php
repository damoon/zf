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
 * @package    Zend_Auth
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * @category   Zend
 * @package    Zend_Auth
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Auth
{
    /**
     * Default session namespace
     */
    const SESSION_NAMESPACE_DEFAULT = 'Zend_Auth';

    /**
     * Default session variable name for authentication token
     */
    const SESSION_TOKEN_NAME_DEFAULT = 'token';

    /**
     * Authentication adapter
     *
     * @var Zend_Auth_Adapter
     */
    protected $_adapter;

    /**
     * Whether or not to automatically use the session for persisting authentication token
     *
     * @var boolean
     */
    protected $_useSession;

    /**
     * Session namespace used for storing authentication token
     *
     * @var string
     */
    protected $_sessionNamespace;

    /**
     * Member name for authentication token
     */
    protected $_sessionTokenName;

    /**
     * Sets the authentication adapter
     *
     * @param  Zend_Auth_Adapter $adapter
     * @param  boolean           $useSession
     * @param  string            $sessionNamespace
     * @param  string            $sessionToken
     * @return void
     */
    public function __construct(Zend_Auth_Adapter $adapter, $useSession = true,
                                $sessionNamespace = self::SESSION_NAMESPACE_DEFAULT,
                                $sessionTokenName = self::SESSION_TOKEN_NAME_DEFAULT)
    {
        $this->_adapter = $adapter;
        $this->setUseSession($useSession);
        $this->setSessionNamespace($sessionNamespace);
        $this->setSessionTokenName($sessionTokenName);
    }

    /**
     * Authenticates against the attached adapter
     *
     * All parameters are passed along to the adapter's authenticate() method.
     *
     * @param  boolean $useSession
     * @return Zend_Auth_Token_Interface
     */
    public function authenticate()
    {
        $args = func_get_args();
        $token = call_user_func_array(array($this->_adapter, __FUNCTION__), $args);

        /**
         * @todo persist token in session if $this->_useSession === true
         */

        return $token;
    }

    /**
     * Returns whether or not the session is used automatically
     *
     * @return boolean
     */
    public function getUseSession()
    {
        return $this->_useSession;
    }

    /**
     * Set whether or not to use the session automatically
     *
     * @param  booolean $useSession
     * @return Zend_Auth Provides a fluent interface
     */
    public function setUseSession($useSession)
    {
        $this->_useSession = (boolean) $useSession;

        return $this;
    }

    /**
     * Returns the session namespace used for storing authentication token
     *
     * @return string
     */
    public function getSessionNamespace()
    {
        return $this->_sessionNamespace;
    }

    /**
     * Sets the session namespace used for storing authentication token
     *
     * @param  string $sessionNamespace
     * @return Zend_Auth Provides a fluent interface
     */
    public function setSessionNamespace($sessionNamespace)
    {
        $this->_sessionNamespace = (string) $sessionNamespace;
    }

    /**
     * Returns the name of the session object member where the authentication token is located
     *
     * @return string
     */
    public function getSessionTokenName()
    {
        return $this->_sessionTokenName;
    }

    /**
     * Sets the name of the session object member where the authentication token is located
     *
     * @param  string $sessionTokenName
     * @return Zend_Auth Provides a fluent interface
     */
    public function setSessionTokenName($sessionTokenName)
    {
        $this->_sessionTokenName = (string) $sessionTokenName;

        return $this;
    }

    public function isLoggedIn()
    {
        /**
         * @todo
         */
    }

    public function logout()
    {
        /**
         * @todo
         */
    }

}
