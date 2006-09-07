<?php

/**
 * @category   Zend
 * @package    Zend_Config
 * @subpackage UnitTests
 */


/**
 * Zend_Config_Array
 */
require_once 'Zend/Config/Array.php';

/**
 * PHPUnit test case
 */
require_once 'PHPUnit/Framework/TestCase.php';


/**
 * @category   Zend
 * @package    Zend_Config
 * @subpackage UnitTests
 */
class Zend_Config_ArrayTest extends PHPUnit_Framework_TestCase
{
    protected $_configFile;

    public function setUp()
    {
        $this->_configFile = dirname(__FILE__) . '/_files/config.php';
    }

    public function testLoadSingleSection()
    {
        $array = Zend_Config_Array::load($this->_configFile, 'all');

        $this->assertEquals('all', $array['hostname']);
        $this->assertEquals('live', $array['db']['name']);
        $this->assertEquals('multi', $array['one']['two']['three']);
        $this->assertNull(@$array['nonexistent']); // property doesn't exist
    }

    public function testSectionInclude()
    {
        $array = Zend_Config_Array::load($this->_configFile, 'staging');
        $this->assertEquals(false, $array['debug']); // only in staging
        $this->assertEquals('thisname', $array['name']); // only in all
        $this->assertEquals('username', $array['db']['user']); // only in all (nested version)
        $this->assertEquals('staging', $array['hostname']); // inherited and overridden
        $this->assertEquals('dbstaging', $array['db']['name']); // inherited and overridden
    }

    public function testMultiDepthExtends()
    {
        $array = Zend_Config_Array::load($this->_configFile, 'other_staging');

        $this->assertEquals('otherStaging', $array['only_in']); // only in other_staging
        $this->assertEquals(false, $array['debug']); // 1 level down: only in staging
        $this->assertEquals('thisname', $array['name']); //  2 levels down: only in all
        $this->assertEquals('username', $array['db']['user']); // 2 levels down: only in all (nested version)
        $this->assertEquals('staging', $array['hostname']); // inherited from two to one and overridden
        $this->assertEquals('dbstaging', $array['db']['name']); // inherited from two to one and overridden
        $this->assertEquals('anotherpwd', $array['db']['pass']); // inherited from two to other_staging and overridden
    }

    public function testErrorNoInitialSection()
    {
        try {
            $array = @Zend_Config_Array::load($this->_configFile);
            $this->fail('An expected Zend_Config_Exception has not been raised');
        } catch (Zend_Config_Exception $expected) {
            $this->assertContains('Section is not set', $expected->getMessage());
        }
    }

    public function testErrorInvalidSection()
    {
        try {
            $array = Zend_Config_Array::load($this->_configFile, 'extendserror');
            $this->fail('An expected Zend_Config_Exception has not been raised');
        } catch (Zend_Config_Exception $expected) {
            $this->assertContains('is not an array', $expected->getMessage());
        }
    }
}
