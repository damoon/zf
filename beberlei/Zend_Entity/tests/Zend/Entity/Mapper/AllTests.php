<?php

require_once dirname(__FILE__)."/../../TestHelper.php";
require_once "Definition/AllTests.php";
require_once "LazyLoad/AllTests.php";
require_once "Loader/AllTests.php";

class Zend_Entity_Mapper_AllTests
{
    static public function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend_Entity_Mapper Tests');
        $suite->addTest(Zend_Entity_Mapper_Definition_AllTests::suite());
        $suite->addTest(Zend_Entity_Mapper_LazyLoad_AllTests::suite());
        $suite->addTest(Zend_Entity_Mapper_Loader_AllTests::suite());

        return $suite;
    }
}