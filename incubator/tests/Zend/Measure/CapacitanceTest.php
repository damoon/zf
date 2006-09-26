<?php
/**
 * @package    Zend_Measure
 * @subpackage UnitTests
 */


/**
 * Zend_Measure_Capacitance
 */
require_once 'Zend/Measure/Capacitance.php';

/**
 * PHPUnit test case
 */
require_once 'PHPUnit/Framework/TestCase.php';


/**
 * @package    Zend_Measure
 * @subpackage UnitTests
 */
class Zend_Measure_CapacitanceTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
    }


    /**
     * test for Capacitance initialisation
     * expected instance
     */
    public function testCapacitanceInit()
    {
        $value = new Zend_Measure_Capacitance('100',Zend_Measure_Capacitance::STANDARD,'de');
        $this->assertTrue($value instanceof Zend_Measure_Capacitance,'Zend_Measure_Capacitance Object not returned');
    }


    /**
     * test for exception unknown type
     * expected exception
     */
    public function testCapacitanceUnknownType()
    {
        try {
            $value = new Zend_Measure_Capacitance('100','Capacitance::UNKNOWN','de');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown value
     * expected exception
     */
    public function testCapacitanceUnknownValue()
    {
        try {
            $value = new Zend_Measure_Capacitance('novalue',Zend_Measure_Capacitance::STANDARD,'de');
            $this->assertTrue(false,'Exception expected because of empty value');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown locale
     * expected root value
     */
    public function testCapacitanceUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Capacitance('100',Zend_Measure_Capacitance::STANDARD,'nolocale');
            $this->assertTrue(false,'Exception expected because of unknown locale');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for positive value
     * expected integer
     */
    public function testCapacitanceValuePositive()
    {
        $value = new Zend_Measure_Capacitance('100',Zend_Measure_Capacitance::STANDARD,'de');
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Capacitance value expected to be a positive integer');
    }


    /**
     * test for negative value
     * expected integer
     */
    public function testCapacitanceValueNegative()
    {
        $value = new Zend_Measure_Capacitance('-100',Zend_Measure_Capacitance::STANDARD,'de');
        $this->assertEquals(-100, $value->getValue(), 'Zend_Measure_Capacitance value expected to be a negative integer');
    }


    /**
     * test for decimal value
     * expected float
     */
    public function testCapacitanceValueDecimal()
    {
        $value = new Zend_Measure_Capacitance('-100,200',Zend_Measure_Capacitance::STANDARD,'de');
        $this->assertEquals(-100.200, $value->getValue(), 'Zend_Measure_Capacitance value expected to be a decimal value');
    }


    /**
     * test for decimal seperated value
     * expected float
     */
    public function testCapacitanceValueDecimalSeperated()
    {
        $value = new Zend_Measure_Capacitance('-100.100,200',Zend_Measure_Capacitance::STANDARD,'de');
        $this->assertEquals(-100100.200, $value->getValue(),'Zend_Measure_Capacitance Object not returned');
    }


    /**
     * test for string with integrated value
     * expected float
     */
    public function testCapacitanceValueString()
    {
        $value = new Zend_Measure_Capacitance('string -100.100,200',Zend_Measure_Capacitance::STANDARD,'de');
        $this->assertEquals(-100100.200, $value->getValue(),'Zend_Measure_Capacitance Object not returned');
    }


    /**
     * test for equality
     * expected true
     */
    public function testCapacitanceEquality()
    {
        $value = new Zend_Measure_Capacitance('string -100.100,200',Zend_Measure_Capacitance::STANDARD,'de');
        $newvalue = new Zend_Measure_Capacitance('otherstring -100.100,200',Zend_Measure_Capacitance::STANDARD,'de');
        $this->assertTrue($value->equals($newvalue),'Zend_Measure_Capacitance Object should be equal');
    }


    /**
     * test for no equality
     * expected false
     */
    public function testCapacitanceNoEquality()
    {
        $value = new Zend_Measure_Capacitance('string -100.100,200',Zend_Measure_Capacitance::STANDARD,'de');
        $newvalue = new Zend_Measure_Capacitance('otherstring -100,200',Zend_Measure_Capacitance::STANDARD,'de');
        $this->assertFalse($value->equals($newvalue),'Zend_Measure_Capacitance Object should be not equal');
    }


    /**
     * test for serialization
     * expected string
     */
    public function testCapacitanceSerialize()
    {
        $value = new Zend_Measure_Capacitance('string -100.100,200',Zend_Measure_Capacitance::STANDARD,'de');
        $serial = $value->serialize();
        $this->assertTrue(!empty($serial),'Zend_Measure_Capacitance not serialized');
    }


    /**
     * test for unserialization
     * expected object
     */
    public function testCapacitanceUnSerialize()
    {
        $value = new Zend_Measure_Capacitance('string -100.100,200',Zend_Measure_Capacitance::STANDARD,'de');
        $serial = $value->serialize();
        $newvalue = unserialize($serial);
        $this->assertTrue($value->equals($newvalue),'Zend_Measure_Capacitance not unserialized');
    }


    /**
     * test for set positive value
     * expected integer
     */
    public function testCapacitanceSetPositive()
    {
        $value = new Zend_Measure_Capacitance('100',Zend_Measure_Capacitance::STANDARD,'de');
        $value->setValue('200',Zend_Measure_Capacitance::STANDARD,'de');
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Capacitance value expected to be a positive integer');
    }


    /**
     * test for set negative value
     * expected integer
     */
    public function testCapacitanceSetNegative()
    {
        $value = new Zend_Measure_Capacitance('-100',Zend_Measure_Capacitance::STANDARD,'de');
        $value->setValue('-200',Zend_Measure_Capacitance::STANDARD,'de');
        $this->assertEquals(-200, $value->getValue(), 'Zend_Measure_Capacitance value expected to be a negative integer');
    }


    /**
     * test for set decimal value
     * expected float
     */
    public function testCapacitanceSetDecimal()
    {
        $value = new Zend_Measure_Capacitance('-100,200',Zend_Measure_Capacitance::STANDARD,'de');
        $value->setValue('-200,200',Zend_Measure_Capacitance::STANDARD,'de');
        $this->assertEquals(-200.200, $value->getValue(), 'Zend_Measure_Capacitance value expected to be a decimal value');
    }


    /**
     * test for set decimal seperated value
     * expected float
     */
    public function testCapacitanceSetDecimalSeperated()
    {
        $value = new Zend_Measure_Capacitance('-100.100,200',Zend_Measure_Capacitance::STANDARD,'de');
        $value->setValue('-200.200,200',Zend_Measure_Capacitance::STANDARD,'de');
        $this->assertEquals(-200200.200, $value->getValue(),'Zend_Measure_Capacitance Object not returned');
    }


    /**
     * test for set string with integrated value
     * expected float
     */
    public function testCapacitanceSetString()
    {
        $value = new Zend_Measure_Capacitance('string -100.100,200',Zend_Measure_Capacitance::STANDARD,'de');
        $value->setValue('otherstring -200.200,200',Zend_Measure_Capacitance::STANDARD,'de');
        $this->assertEquals(-200200.200, $value->getValue(),'Zend_Measure_Capacitance Object not returned');
    }


    /**
     * test for exception unknown type
     * expected exception
     */
    public function testCapacitanceSetUnknownType()
    {
        try {
            $value = new Zend_Measure_Capacitance('100',Zend_Measure_Capacitance::STANDARD,'de');
            $value->setValue('otherstring -200.200,200','Capacitance::UNKNOWN','de');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown value
     * expected exception
     */
    public function testCapacitanceSetUnknownValue()
    {
        try {
            $value = new Zend_Measure_Capacitance('100',Zend_Measure_Capacitance::STANDARD,'de');
            $value->setValue('novalue',Zend_Measure_Capacitance::STANDARD,'de');
            $this->assertTrue(false,'Exception expected because of empty value');
        } catch (Exception $e) {
            return; // Test OK
        }
    }


    /**
     * test for exception unknown locale
     * expected exception
     */
    public function testCapacitanceSetUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Capacitance('100',Zend_Measure_Capacitance::STANDARD,'de');
            $value->setValue('200',Zend_Measure_Capacitance::STANDARD,'nolocale');
            $this->assertTrue(false,'Exception expected because of unknown locale');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown locale
     * expected exception
     */
    public function testCapacitanceSetWithNoLocale()
    {
        $value = new Zend_Measure_Capacitance('100', Zend_Measure_Capacitance::STANDARD, 'de');
        $value->setValue('200', Zend_Measure_Capacitance::STANDARD);
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Capacitance value expected to be a positive integer');
    }


    /**
     * test setting type
     * expected new type
     */
    public function testCapacitanceSetType()
    {
        $value = new Zend_Measure_Capacitance('-100',Zend_Measure_Capacitance::STANDARD,'de');
        $value->setType(Zend_Measure_Capacitance::NANOFARAD);
        $this->assertEquals($value->getType(), Zend_Measure_Capacitance::NANOFARAD, 'Zend_Measure_Capacitance type expected');
    }


    /**
     * test setting unknown type
     * expected new type
     */
    public function testCapacitanceSetTypeFailed()
    {
        try {
            $value = new Zend_Measure_Capacitance('-100',Zend_Measure_Capacitance::STANDARD,'de');
            $value->setType('Capacitance::UNKNOWN');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // OK
        }
    }


    /**
     * test toString
     * expected string
     */
    public function testCapacitanceToString()
    {
        $value = new Zend_Measure_Capacitance('-100',Zend_Measure_Capacitance::STANDARD,'de');
        $this->assertEquals($value->toString(), '-100 F', 'Value -100 F expected');
    }


    /**
     * test __toString
     * expected string
     */
    public function testCapacitance_ToString()
    {
        $value = new Zend_Measure_Capacitance('-100',Zend_Measure_Capacitance::STANDARD,'de');
        $this->assertEquals($value->__toString(), '-100 F', 'Value -100 F expected');
    }


    /**
     * test getConversionList
     * expected array
     */
    public function testCapacitanceConversionList()
    {
        $value = new Zend_Measure_Capacitance('-100',Zend_Measure_Capacitance::STANDARD,'de');
        $unit  = $value->getConversionList();
        $this->assertTrue(is_array($unit), 'Array expected');
    }
}
