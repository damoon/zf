<?php
require_once 'Zend/XmlRpc/Server.php';
require_once 'Zend/XmlRpc/Server/Cache.php';
require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'PHPUnit2/Framework/IncompleteTestError.php';

/**
 * Test case for Zend_XmlRpc_Server_Cache
 *
 * @package Zend_XmlRpc
 * @subpackage UnitTests
 * @version $Id: $
 */
class Zend_XmlRpc_Server_CacheTest extends PHPUnit2_Framework_TestCase 
{
    /**
     * Zend_XmlRpc_Server object
     * @var Zend_XmlRpc_Server
     */
    protected $_server;

    /**
     * Local file for caching
     * @var string 
     */
    protected $_file;

    /**
     * Setup environment
     */
    public function setUp() 
    {
        $this->_file = realpath(dirname(__FILE__)) . '/xmlrpc.cache';
        $this->_server = new Zend_XmlRpc_Server();
        $this->_server->setClass('Zend_XmlRpc_Server_CacheTest', 'cache');
    }

    /**
     * Teardown environment
     */
    public function tearDown() 
    {
        if (file_exists($this->_file)) {
            unlink($this->_file);
        }
        unset($this->_server);
    }

    /**
     * Tests functionality of both get() and save()
     */
    public function testGetSave()
    {
        // Remove this line once the test has been written
        if (!is_writeable('./')) {
            throw new PHPUnit2_Framework_IncompleteTestError('Directory not writeable');
        }

        $this->assertTrue(Zend_XmlRpc_Server_Cache::save($this->_file, $this->_server));
        $expected = $this->_server->listMethods();
        $server = new Zend_XmlRpc_Server();
        $this->assertTrue(Zend_XmlRpc_Server_Cache::get($this->_file, $server));
        $actual = $server->listMethods();

        $this->assertSame($expected, $actual);
    }

    /**
     * Zend_XmlRpc_Server_Cache::delete() test
     */
    public function testDelete()
    {
        // Remove this line once the test has been written
        if (!is_writeable('./')) {
            throw new PHPUnit2_Framework_IncompleteTestError('Directory not writeable');
        }

        $this->assertTrue(Zend_XmlRpc_Server_Cache::save($this->_file, $this->_server));
        $this->assertTrue(Zend_XmlRpc_Server_Cache::delete($this->_file));
    }
}
