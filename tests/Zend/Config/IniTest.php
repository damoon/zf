<?php

/**
 * @category   Zend
 * @package    Zend_Config
 * @subpackage UnitTests
 */


/**
 * Zend_Config_Ini
 */
require_once 'Zend/Config/Ini.php';

/**
 * PHPUnit test case
 */
require_once 'PHPUnit/Framework/TestCase.php';


/**
 * @category   Zend
 * @package    Zend_Config
 * @subpackage UnitTests
 */
class Zend_Config_IniTest extends PHPUnit_Framework_TestCase
{
    protected $_iniFileConfig;
    protected $_iniFileNested;

    public function setUp()
    {
        $this->_iniFileConfig = dirname(__FILE__) . '/_files/config.ini';
    }

    public function testLoadSingleSection()
    {
        $config = new Zend_Config_Ini($this->_iniFileConfig, 'all');

        $this->assertEquals('all', $config->hostname);
        $this->assertEquals('live', $config->db->name);
        $this->assertEquals('multi', $config->one->two->three);
        $this->assertNull(@$config->nonexistent); // property doesn't exist
    }

    public function testSectionInclude()
    {
        $config = new Zend_Config_Ini($this->_iniFileConfig, 'staging');

        $this->assertEquals('', $config->debug); // only in staging
        $this->assertEquals('thisname', $config->name); // only in all
        $this->assertEquals('username', $config->db->user); // only in all (nested version)
        $this->assertEquals('staging', $config->hostname); // inherited and overridden
        $this->assertEquals('dbstaging', $config->db->name); // inherited and overridden
    }

    public function testTrueValues()
    {
        $config = new Zend_Config_Ini($this->_iniFileConfig, 'debug');

        $this->assertType('string', $config->debug);
        $this->assertEquals('1', $config->debug);
        $this->assertType('string', $config->values->changed);
        $this->assertEquals('1', $config->values->changed);
    }

    public function testEmptyValues()
    {
        $config = new Zend_Config_Ini($this->_iniFileConfig, 'debug');

        $this->assertType('string', $config->special->no);
        $this->assertEquals('', $config->special->no);
        $this->assertType('string', $config->special->null);
        $this->assertEquals('', $config->special->null);
        $this->assertType('string', $config->special->false);
        $this->assertEquals('', $config->special->false);
    }

    public function testMultiDepthExtends()
    {
        $config = new Zend_Config_Ini($this->_iniFileConfig, 'other_staging');

        $this->assertEquals('otherStaging', $config->only_in); // only in other_staging
        $this->assertEquals('', $config->debug); // 1 level down: only in staging
        $this->assertEquals('thisname', $config->name); // 2 levels down: only in all
        $this->assertEquals('username', $config->db->user); // 2 levels down: only in all (nested version)
        $this->assertEquals('staging', $config->hostname); // inherited from two to one and overridden
        $this->assertEquals('dbstaging', $config->db->name); // inherited from two to one and overridden
        $this->assertEquals('anotherpwd', $config->db->pass); // inherited from two to other_staging and overridden
    }

    public function testErrorNoInitialSection()
    {
        try {
            $config = @new Zend_Config_Ini($this->_iniFileConfig);
            $this->fail('An expected Zend_Config_Exception has not been raised');
        } catch (Zend_Config_Exception $expected) {
            $this->assertContains('Section is not set', $expected->getMessage());
        }
    }

    public function testErrorNoExtendsSection()
    {
        try {
            $config = new Zend_Config_Ini($this->_iniFileConfig, 'extendserror');
            $this->fail('An expected Zend_Config_Exception has not been raised');
        } catch (Zend_Config_Exception $expected) {
            $this->assertContains('cannot be found', $expected->getMessage());
        }
    }

    public function testInvalidKeys()
    {
        $sections = array('leadingdot', 'onedot', 'twodots', 'threedots', 'trailingdot');
        foreach ($sections as $section) {
            try {
                $config = new Zend_Config_Ini($this->_iniFileConfig, $section);
                $this->fail('An expected Zend_Config_Exception has not been raised');
            } catch (Zend_Config_Exception $expected) {
                $this->assertContains('Invalid key', $expected->getMessage());
            }
        }
    }

}
