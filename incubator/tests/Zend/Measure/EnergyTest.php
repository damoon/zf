<?php
/**
 * @package    Zend_Measure
 * @subpackage UnitTests
 */


/**
 * Zend_Measure_Energy
 */
require_once 'Zend/Measure/Energy.php';

/**
 * PHPUnit2 test case
 */
require_once 'PHPUnit2/Framework/TestCase.php';


/**
 * @package    Zend_Measure
 * @subpackage UnitTests
 */
class Zend_Measure_EnergyTest extends PHPUnit2_Framework_TestCase
{

    public function setUp()
    {
    }


    /**
     * test for Energy initialisation
     * expected instance
     */
    public function testEnergyInit()
    {
        $value = new Zend_Measure_Energy('100',Zend_Measure_Energy::STANDARD,'de');
        $this->assertTrue($value instanceof Zend_Measure_Energy,'Zend_Measure_Energy Object not returned');
    }


    /**
     * test for exception unknown type
     * expected exception
     */
    public function testEnergyUnknownType()
    {
        try {
            $value = new Zend_Measure_Energy('100','Energy::UNKNOWN','de');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown value
     * expected exception
     */
    public function testEnergyUnknownValue()
    {
        try {
            $value = new Zend_Measure_Energy('novalue',Zend_Measure_Energy::STANDARD,'de');
            $this->assertTrue(false,'Exception expected because of empty value');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown locale
     * expected root value
     */
    public function testEnergyUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Energy('100',Zend_Measure_Energy::STANDARD,'nolocale');
            $this->assertTrue(false,'Exception expected because of unknown locale');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for positive value
     * expected integer
     */
    public function testEnergyValuePositive()
    {
        $value = new Zend_Measure_Energy('100',Zend_Measure_Energy::STANDARD,'de');
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Energy value expected to be a positive integer');
    }


    /**
     * test for negative value
     * expected integer
     */
    public function testEnergyValueNegative()
    {
        $value = new Zend_Measure_Energy('-100',Zend_Measure_Energy::STANDARD,'de');
        $this->assertEquals(-100, $value->getValue(), 'Zend_Measure_Energy value expected to be a negative integer');
    }


    /**
     * test for decimal value
     * expected float
     */
    public function testEnergyValueDecimal()
    {
        $value = new Zend_Measure_Energy('-100,200',Zend_Measure_Energy::STANDARD,'de');
        $this->assertEquals(-100.200, $value->getValue(), 'Zend_Measure_Energy value expected to be a decimal value');
    }


    /**
     * test for decimal seperated value
     * expected float
     */
    public function testEnergyValueDecimalSeperated()
    {
        $value = new Zend_Measure_Energy('-100.100,200',Zend_Measure_Energy::STANDARD,'de');
        $this->assertEquals(-100100.200, $value->getValue(),'Zend_Measure_Energy Object not returned');
    }


    /**
     * test for string with integrated value
     * expected float
     */
    public function testEnergyValueString()
    {
        $value = new Zend_Measure_Energy('string -100.100,200',Zend_Measure_Energy::STANDARD,'de');
        $this->assertEquals(-100100.200, $value->getValue(),'Zend_Measure_Energy Object not returned');
    }


    /**
     * test for equality
     * expected true
     */
    public function testEnergyEquality()
    {
        $value = new Zend_Measure_Energy('string -100.100,200',Zend_Measure_Energy::STANDARD,'de');
        $newvalue = new Zend_Measure_Energy('otherstring -100.100,200',Zend_Measure_Energy::STANDARD,'de');
        $this->assertTrue($value->equals($newvalue),'Zend_Measure_Energy Object should be equal');
    }


    /**
     * test for no equality
     * expected false
     */
    public function testEnergyNoEquality()
    {
        $value = new Zend_Measure_Energy('string -100.100,200',Zend_Measure_Energy::STANDARD,'de');
        $newvalue = new Zend_Measure_Energy('otherstring -100,200',Zend_Measure_Energy::STANDARD,'de');
        $this->assertFalse($value->equals($newvalue),'Zend_Measure_Energy Object should be not equal');
    }


    /**
     * test for serialization
     * expected string
     */
    public function testEnergySerialize()
    {
        $value = new Zend_Measure_Energy('string -100.100,200',Zend_Measure_Energy::STANDARD,'de');
        $serial = $value->serialize();
        $this->assertTrue(!empty($serial),'Zend_Measure_Energy not serialized');
    }


    /**
     * test for unserialization
     * expected object
     */
    public function testEnergyUnSerialize()
    {
        $value = new Zend_Measure_Energy('string -100.100,200',Zend_Measure_Energy::STANDARD,'de');
        $serial = $value->serialize();
        $newvalue = unserialize($serial);
        $this->assertTrue($value->equals($newvalue),'Zend_Measure_Energy not unserialized');
    }


    /**
     * test for set positive value
     * expected integer
     */
    public function testEnergySetPositive()
    {
        $value = new Zend_Measure_Energy('100',Zend_Measure_Energy::STANDARD,'de');
        $value->setValue('200',Zend_Measure_Energy::STANDARD,'de');
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Energy value expected to be a positive integer');
    }


    /**
     * test for set negative value
     * expected integer
     */
    public function testEnergySetNegative()
    {
        $value = new Zend_Measure_Energy('-100',Zend_Measure_Energy::STANDARD,'de');
        $value->setValue('-200',Zend_Measure_Energy::STANDARD,'de');
        $this->assertEquals(-200, $value->getValue(), 'Zend_Measure_Energy value expected to be a negative integer');
    }


    /**
     * test for set decimal value
     * expected float
     */
    public function testEnergySetDecimal()
    {
        $value = new Zend_Measure_Energy('-100,200',Zend_Measure_Energy::STANDARD,'de');
        $value->setValue('-200,200',Zend_Measure_Energy::STANDARD,'de');
        $this->assertEquals(-200.200, $value->getValue(), 'Zend_Measure_Energy value expected to be a decimal value');
    }


    /**
     * test for set decimal seperated value
     * expected float
     */
    public function testEnergySetDecimalSeperated()
    {
        $value = new Zend_Measure_Energy('-100.100,200',Zend_Measure_Energy::STANDARD,'de');
        $value->setValue('-200.200,200',Zend_Measure_Energy::STANDARD,'de');
        $this->assertEquals(-200200.200, $value->getValue(),'Zend_Measure_Energy Object not returned');
    }


    /**
     * test for set string with integrated value
     * expected float
     */
    public function testEnergySetString()
    {
        $value = new Zend_Measure_Energy('string -100.100,200',Zend_Measure_Energy::STANDARD,'de');
        $value->setValue('otherstring -200.200,200',Zend_Measure_Energy::STANDARD,'de');
        $this->assertEquals(-200200.200, $value->getValue(),'Zend_Measure_Energy Object not returned');
    }


    /**
     * test for exception unknown type
     * expected exception
     */
    public function testEnergySetUnknownType()
    {
        try {
            $value = new Zend_Measure_Energy('100',Zend_Measure_Energy::STANDARD,'de');
            $value->setValue('otherstring -200.200,200','Energy::UNKNOWN','de');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown value
     * expected exception
     */
    public function testEnergySetUnknownValue()
    {
        try {
            $value = new Zend_Measure_Energy('100',Zend_Measure_Energy::STANDARD,'de');
            $value->setValue('novalue',Zend_Measure_Energy::STANDARD,'de');
            $this->assertTrue(false,'Exception expected because of empty value');
        } catch (Exception $e) {
            return; // Test OK
        }
    }


    /**
     * test for exception unknown locale
     * expected exception
     */
    public function testEnergySetUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Energy('100',Zend_Measure_Energy::STANDARD,'de');
            $value->setValue('200',Zend_Measure_Energy::STANDARD,'nolocale');
            $this->assertTrue(false,'Exception expected because of unknown locale');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test setting type
     * expected new type
     */
    public function testEnergySetType()
    {
        $value = new Zend_Measure_Energy('-100',Zend_Measure_Energy::STANDARD,'de');
        $value->setType(Zend_Measure_Energy::ERG);
        $this->assertEquals($value->getType(), Zend_Measure_Energy::ERG, 'Zend_Measure_Energy type expected');
    }


    /**
     * test setting computed type
     * expected new type
     */
    public function testEnergySetComputedType1()
    {
        $value = new Zend_Measure_Energy('-100',Zend_Measure_Energy::ERG,'de');
        $value->setType(Zend_Measure_Energy::KILOTON);
        $this->assertEquals($value->getType(), Zend_Measure_Energy::KILOTON, 'Zend_Measure_Energy type expected');
    }


    /**
     * test setting computed type
     * expected new type
     */
    public function testEnergySetComputedType2()
    {
        $value = new Zend_Measure_Energy('-100',Zend_Measure_Energy::KILOTON,'de');
        $value->setType(Zend_Measure_Energy::ERG);
        $this->assertEquals($value->getType(), Zend_Measure_Energy::ERG, 'Zend_Measure_Energy type expected');
    }


    /**
     * test setting unknown type
     * expected new type
     */
    public function testEnergySetTypeFailed()
    {
        try {
            $value = new Zend_Measure_Energy('-100',Zend_Measure_Energy::STANDARD,'de');
            $value->setType('Energy::UNKNOWN');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // OK
        }
    }


    /**
     * test toString
     * expected string
     */
    public function testEnergyToString()
    {
        $value = new Zend_Measure_Energy('-100',Zend_Measure_Energy::STANDARD,'de');
        $this->assertEquals($value->toString(), '-100 J', 'Value -100 J expected');
    }


    /**
     * test __toString
     * expected string
     */
    public function testEnergy_ToString()
    {
        $value = new Zend_Measure_Energy('-100',Zend_Measure_Energy::STANDARD,'de');
        $this->assertEquals($value->__toString(), '-100 J', 'Value -100 J expected');
    }
}
