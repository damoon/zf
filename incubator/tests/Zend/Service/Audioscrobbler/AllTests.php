<?php
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Service_Audioscrobbler_AllTests::main');
}

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'ProfileTest.php';
require_once 'TopartistsTest.php';

class AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend Framework - Zend_Service_Audioscrobbler');

        $suite->addTestSuite( 'Zend_Service_Audioscrobbler_ProfileTest' );
        $suite->addTestSuite('Zend_Service_Audioscrobbler_TopartistsTest');

        return $suite;
    }
}

