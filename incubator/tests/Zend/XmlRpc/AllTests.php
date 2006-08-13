<?php
if (!defined('PHPUnit2_MAIN_METHOD')) {
    define('PHPUnit2_MAIN_METHOD', 'Zend_XmlRpc_AllTests::main');
}

require_once 'PHPUnit2/Framework/TestSuite.php';
require_once 'PHPUnit2/TextUI/TestRunner.php';

require_once 'Zend/XmlRpc/ServerTest.php';
require_once 'Zend/XmlRpc/Server/CacheTest.php';
require_once 'Zend/XmlRpc/Server/FaultTest.php';

class Zend_XmlRpc_AllTests
{
    public static function main()
    {
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit2_Framework_TestSuite('Zend Framework - Zend_XmlRpc');

        $suite->addTestSuite('Zend_XmlRpc_ServerTest');
        $suite->addTestSuite('Zend_XmlRpc_Server_CacheTest');
        $suite->addTestSuite('Zend_XmlRpc_Server_FaultTest');
       
        return $suite;
    }
}

if (PHPUnit2_MAIN_METHOD == 'Zend_XmlRpc_AllTests::main') {
    Zend_Mail_AllTests::main();
}
