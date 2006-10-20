<?php
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Controller_AllTests::main');
}

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

// error_reporting(E_ALL);

require_once 'RouterTest.php';
require_once 'Request/HttpTest.php';

class Zend_Controller_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend Framework - Zend_Controller');

        $suite->addTestSuite('Zend_Controller_RouterTest');
        $suite->addTestSuite('Zend_Controller_Request_HttpTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_Config_AllTests::main') {
    Zend_Config_AllTests::main();
}
