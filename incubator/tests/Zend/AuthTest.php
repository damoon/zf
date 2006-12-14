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
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * Zend_Auth
 */
require_once 'Zend/Auth.php';


/**
 * Zend_Auth_Adapter
 */
require_once 'Zend/Auth/Adapter.php';


/**
 * Zend_Auth_Token_Interface
 */
require_once 'Zend/Auth/Token/Interface.php';


/**
 * PHPUnit_Framework_TestCase
 */
require_once 'PHPUnit/Framework/TestCase.php';


/**
 * @category   Zend
 * @package    Zend_Auth
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_AuthTest extends PHPUnit_Framework_TestCase
{
    /**
     * Ensure expected behavior upon sucessful authentication
     *
     * @return void
     */
    public function testSuccess()
    {
        $auth = new Zend_Auth(new Zend_AuthTest_Success_Adapter(), false);
        $options = array();
        $token = $auth->authenticate($options);
        $this->assertTrue($token->isValid());
        $this->assertTrue('someIdentity' === $token->getIdentity());
        $this->assertTrue(null === $token->getMessage());
    }
}


class Zend_AuthTest_Success_Adapter extends Zend_Auth_Adapter
{
    public function authenticate(array $options)
    {
        return new Zend_AuthTest_Simple_Token(true, 'someIdentity');
    }
}


class Zend_AuthTest_Simple_Token implements Zend_Auth_Token_Interface
{
    protected $_valid;
    protected $_identity;
    protected $_message;

    public function __construct($valid, $identity, $message = null)
    {
        $this->_valid    = $valid;
        $this->_identity = $identity;
        $this->_message  = $message;
    }

    public function isValid()
    {
        return $this->_valid;
    }

    public function getIdentity()
    {
        return $this->_identity;
    }

    public function getMessage()
    {
        return $this->_message;
    }

}