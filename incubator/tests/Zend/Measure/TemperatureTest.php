<?php
/**
 * @package    Zend_Measure
 * @subpackage UnitTests
 */


/**
 * Zend_Measure_Temperature
 */
require_once 'Zend/Measure/Temperature.php';

/**
 * PHPUnit2 test case
 */
require_once 'PHPUnit2/Framework/TestCase.php';


/**
 * @package    Zend_Measure
 * @subpackage UnitTests
 */
class Zend_Measure_TemperatureTest extends PHPUnit2_Framework_TestCase
{

    public function setUp()
    {
    }


    /**
     * test for Temperature initialisation
     * expected instance
     */
    public function testTemperatureInit()
    {
        $value = new Zend_Measure_Temperature('100',Zend_Measure_Temperature::STANDARD,'de');
        $this->assertTrue($value instanceof Zend_Measure_Temperature,'Zend_Measure_Temperature Object not returned');
    }


    /**
     * test for exception unknown type
     * expected exception
     */
    public function testTemperatureUnknownType()
    {
        try {
            $value = new Zend_Measure_Temperature('100','Temperature::UNKNOWN','de');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown value
     * expected exception
     */
    public function testTemperatureUnknownValue()
    {
        try {
            $value = new Zend_Measure_Temperature('novalue',Zend_Measure_Temperature::STANDARD,'de');
            $this->assertTrue(false,'Exception expected because of empty value');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown locale
     * expected root value
     */
    public function testTemperatureUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Temperature('100',Zend_Measure_Temperature::STANDARD,'nolocale');
            $this->assertTrue(false,'Exception expected because of unknown locale');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for positive value
     * expected integer
     */
    public function testTemperatureValuePositive()
    {
        $value = new Zend_Measure_Temperature('100',Zend_Measure_Temperature::STANDARD,'de');
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Temperature value expected to be a positive integer');
    }


    /**
     * test for negative value
     * expected integer
     */
    public function testTemperatureValueNegative()
    {
        $value = new Zend_Measure_Temperature('-100',Zend_Measure_Temperature::STANDARD,'de');
        $this->assertEquals(-100, $value->getValue(), 'Zend_Measure_Temperature value expected to be a negative integer');
    }


    /**
     * test for decimal value
     * expected float
     */
    public function testTemperatureValueDecimal()
    {
        $value = new Zend_Measure_Temperature('-100,200',Zend_Measure_Temperature::STANDARD,'de');
        $this->assertEquals(-100.200, $value->getValue(), 'Zend_Measure_Temperature value expected to be a decimal value');
    }


    /**
     * test for decimal seperated value
     * expected float
     */
    public function testTemperatureValueDecimalSeperated()
    {
        $value = new Zend_Measure_Temperature('-100.100,200',Zend_Measure_Temperature::STANDARD,'de');
        $this->assertEquals(-100100.200, $value->getValue(),'Zend_Measure_Temperature Object not returned');
    }


    /**
     * test for string with integrated value
     * expected float
     */
    public function testTemperatureValueString()
    {
        $value = new Zend_Measure_Temperature('string -100.100,200',Zend_Measure_Temperature::STANDARD,'de');
        $this->assertEquals(-100100.200, $value->getValue(),'Zend_Measure_Temperature Object not returned');
    }


    /**
     * test for equality
     * expected true
     */
    public function testTemperatureEquality()
    {
        $value = new Zend_Measure_Temperature('string -100.100,200',Zend_Measure_Temperature::STANDARD,'de');
        $newvalue = new Zend_Measure_Temperature('otherstring -100.100,200',Zend_Measure_Temperature::STANDARD,'de');
        $this->assertTrue($value->equals($newvalue),'Zend_Measure_Temperature Object should be equal');
    }


    /**
     * test for no equality
     * expected false
     */
    public function testTemperatureNoEquality()
    {
        $value = new Zend_Measure_Temperature('string -100.100,200',Zend_Measure_Temperature::STANDARD,'de');
        $newvalue = new Zend_Measure_Temperature('otherstring -100,200',Zend_Measure_Temperature::STANDARD,'de');
        $this->assertFalse($value->equals($newvalue),'Zend_Measure_Temperature Object should be not equal');
    }


    /**
     * test for serialization
     * expected string
     */
    public function testTemperatureSerialize()
    {
        $value = new Zend_Measure_Temperature('string -100.100,200',Zend_Measure_Temperature::STANDARD,'de');
        $serial = $value->serialize();
        $this->assertTrue(!empty($serial),'Zend_Measure_Temperature not serialized');
    }


    /**
     * test for unserialization
     * expected object
     */
    public function testTemperatureUnSerialize()
    {
        $value = new Zend_Measure_Temperature('string -100.100,200',Zend_Measure_Temperature::STANDARD,'de');
        $serial = $value->serialize();
        $newvalue = unserialize($serial);
        $this->assertTrue($value->equals($newvalue),'Zend_Measure_Temperature not unserialized');
    }


    /**
     * test for set positive value
     * expected integer
     */
    public function testTemperatureSetPositive()
    {
        $value = new Zend_Measure_Temperature('100',Zend_Measure_Temperature::STANDARD,'de');
        $value->setValue('200',Zend_Measure_Temperature::STANDARD,'de');
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Temperature value expected to be a positive integer');
    }


    /**
     * test for set negative value
     * expected integer
     */
    public function testTemperatureSetNegative()
    {
        $value = new Zend_Measure_Temperature('-100',Zend_Measure_Temperature::STANDARD,'de');
        $value->setValue('-200',Zend_Measure_Temperature::STANDARD,'de');
        $this->assertEquals(-200, $value->getValue(), 'Zend_Measure_Temperature value expected to be a negative integer');
    }


    /**
     * test for set decimal value
     * expected float
     */
    public function testTemperatureSetDecimal()
    {
        $value = new Zend_Measure_Temperature('-100,200',Zend_Measure_Temperature::STANDARD,'de');
        $value->setValue('-200,200',Zend_Measure_Temperature::STANDARD,'de');
        $this->assertEquals(-200.200, $value->getValue(), 'Zend_Measure_Temperature value expected to be a decimal value');
    }


    /**
     * test for set decimal seperated value
     * expected float
     */
    public function testTemperatureSetDecimalSeperated()
    {
        $value = new Zend_Measure_Temperature('-100.100,200',Zend_Measure_Temperature::STANDARD,'de');
        $value->setValue('-200.200,200',Zend_Measure_Temperature::STANDARD,'de');
        $this->assertEquals(-200200.200, $value->getValue(),'Zend_Measure_Temperature Object not returned');
    }


    /**
     * test for set string with integrated value
     * expected float
     */
    public function testTemperatureSetString()
    {
        $value = new Zend_Measure_Temperature('string -100.100,200',Zend_Measure_Temperature::STANDARD,'de');
        $value->setValue('otherstring -200.200,200',Zend_Measure_Temperature::STANDARD,'de');
        $this->assertEquals(-200200.200, $value->getValue(),'Zend_Measure_Temperature Object not returned');
    }


    /**
     * test for exception unknown type
     * expected exception
     */
    public function testTemperatureSetUnknownType()
    {
        try {
            $value = new Zend_Measure_Temperature('100',Zend_Measure_Temperature::STANDARD,'de');
            $value->setValue('otherstring -200.200,200','Temperature::UNKNOWN','de');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown value
     * expected exception
     */
    public function testTemperatureSetUnknownValue()
    {
        try {
            $value = new Zend_Measure_Temperature('100',Zend_Measure_Temperature::STANDARD,'de');
            $value->setValue('novalue',Zend_Measure_Temperature::STANDARD,'de');
            $this->assertTrue(false,'Exception expected because of empty value');
        } catch (Exception $e) {
            return; // Test OK
        }
    }


    /**
     * test for exception unknown locale
     * expected exception
     */
    public function testTemperatureSetUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Temperature('100',Zend_Measure_Temperature::STANDARD,'de');
            $value->setValue('200',Zend_Measure_Temperature::STANDARD,'nolocale');
            $this->assertTrue(false,'Exception expected because of unknown locale');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test setting type
     * expected new type
     */
    public function testTemperatureSetType()
    {
        $value = new Zend_Measure_Temperature('-100',Zend_Measure_Temperature::STANDARD,'de');
        $value->setType(Zend_Measure_Temperature::KELVIN);
        $this->assertEquals($value->getType(), Zend_Measure_Temperature::KELVIN, 'Zend_Measure_Temperature type expected');
    }


    /**
     * test setting type
     * expected new type
     */
    public function testTemperatureSetType1()
    {
        $value = new Zend_Measure_Temperature('-100',Zend_Measure_Temperature::FAHRENHEIT,'de');
        $value->setType(Zend_Measure_Temperature::REAUMUR);
        $this->assertEquals($value->getType(), Zend_Measure_Temperature::REAUMUR, 'Zend_Measure_Temperature type expected');
    }


    /**
     * test setting type
     * expected new type
     */
    public function testTemperatureSetType2()
    {
        $value = new Zend_Measure_Temperature('-100',Zend_Measure_Temperature::REAUMUR,'de');
        $value->setType(Zend_Measure_Temperature::FAHRENHEIT);
        $this->assertEquals($value->getType(), Zend_Measure_Temperature::FAHRENHEIT, 'Zend_Measure_Temperature type expected');
    }


    /**
     * test setting unknown type
     * expected new type
     */
    public function testTemperatureSetTypeFailed()
    {
        try {
            $value = new Zend_Measure_Temperature('-100',Zend_Measure_Temperature::STANDARD,'de');
            $value->setType('Temperature::UNKNOWN');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // OK
        }
    }


    /**
     * test toString
     * expected string
     */
    public function testTemperatureToString()
    {
        $value = new Zend_Measure_Temperature('-100',Zend_Measure_Temperature::STANDARD,'de');
        $this->assertEquals($value->toString(), '-100 �K', 'Value -100 �K expected');
    }


    /**
     * test __toString
     * expected string
     */
    public function testTemperature_ToString()
    {
        $value = new Zend_Measure_Temperature('-100',Zend_Measure_Temperature::STANDARD,'de');
        $this->assertEquals($value->__toString(), '-100 �K', 'Value -100 �K expected');
    }


    /**
     * test getConversionList
     * expected array
     */
    public function testTemperatureConversionList()
    {
        $value = new Zend_Measure_Temperature('-100',Zend_Measure_Temperature::STANDARD,'de');
        $unit  = $value->getConversionList();
        $this->assertTrue(is_array($unit), 'Array expected');
    }

}
