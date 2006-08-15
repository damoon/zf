<?php
/**
 * @package    Zend_Measure
 * @subpackage UnitTests
 */


/**
 * Zend_Measure_Lightness
 */
require_once 'Zend/Measure/Lightness.php';

/**
 * PHPUnit2 test case
 */
require_once 'PHPUnit2/Framework/TestCase.php';


/**
 * @package    Zend_Measure
 * @subpackage UnitTests
 */
class Zend_Measure_LightnessTest extends PHPUnit2_Framework_TestCase
{

    public function setUp()
    {
    }


    /**
     * test for Lightness initialisation
     * expected instance
     */
    public function testLightnessInit()
    {
        $value = new Zend_Measure_Lightness('100',Zend_Measure_Lightness::STANDARD,'de');
        $this->assertTrue($value instanceof Zend_Measure_Lightness,'Zend_Measure_Lightness Object not returned');
    }


    /**
     * test for exception unknown type
     * expected exception
     */
    public function testLightnessUnknownType()
    {
        try {
            $value = new Zend_Measure_Lightness('100','Lightness::UNKNOWN','de');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown value
     * expected exception
     */
    public function testLightnessUnknownValue()
    {
        try {
            $value = new Zend_Measure_Lightness('novalue',Zend_Measure_Lightness::STANDARD,'de');
            $this->assertTrue(false,'Exception expected because of empty value');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown locale
     * expected root value
     */
    public function testLightnessUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Lightness('100',Zend_Measure_Lightness::STANDARD,'nolocale');
            $this->assertTrue(false,'Exception expected because of unknown locale');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for positive value
     * expected integer
     */
    public function testLightnessValuePositive()
    {
        $value = new Zend_Measure_Lightness('100',Zend_Measure_Lightness::STANDARD,'de');
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Lightness value expected to be a positive integer');
    }


    /**
     * test for negative value
     * expected integer
     */
    public function testLightnessValueNegative()
    {
        $value = new Zend_Measure_Lightness('-100',Zend_Measure_Lightness::STANDARD,'de');
        $this->assertEquals(-100, $value->getValue(), 'Zend_Measure_Lightness value expected to be a negative integer');
    }


    /**
     * test for decimal value
     * expected float
     */
    public function testLightnessValueDecimal()
    {
        $value = new Zend_Measure_Lightness('-100,200',Zend_Measure_Lightness::STANDARD,'de');
        $this->assertEquals(-100.200, $value->getValue(), 'Zend_Measure_Lightness value expected to be a decimal value');
    }


    /**
     * test for decimal seperated value
     * expected float
     */
    public function testLightnessValueDecimalSeperated()
    {
        $value = new Zend_Measure_Lightness('-100.100,200',Zend_Measure_Lightness::STANDARD,'de');
        $this->assertEquals(-100100.200, $value->getValue(),'Zend_Measure_Lightness Object not returned');
    }


    /**
     * test for string with integrated value
     * expected float
     */
    public function testLightnessValueString()
    {
        $value = new Zend_Measure_Lightness('string -100.100,200',Zend_Measure_Lightness::STANDARD,'de');
        $this->assertEquals(-100100.200, $value->getValue(),'Zend_Measure_Lightness Object not returned');
    }


    /**
     * test for equality
     * expected true
     */
    public function testLightnessEquality()
    {
        $value = new Zend_Measure_Lightness('string -100.100,200',Zend_Measure_Lightness::STANDARD,'de');
        $newvalue = new Zend_Measure_Lightness('otherstring -100.100,200',Zend_Measure_Lightness::STANDARD,'de');
        $this->assertTrue($value->equals($newvalue),'Zend_Measure_Lightness Object should be equal');
    }


    /**
     * test for no equality
     * expected false
     */
    public function testLightnessNoEquality()
    {
        $value = new Zend_Measure_Lightness('string -100.100,200',Zend_Measure_Lightness::STANDARD,'de');
        $newvalue = new Zend_Measure_Lightness('otherstring -100,200',Zend_Measure_Lightness::STANDARD,'de');
        $this->assertFalse($value->equals($newvalue),'Zend_Measure_Lightness Object should be not equal');
    }


    /**
     * test for serialization
     * expected string
     */
    public function testLightnessSerialize()
    {
        $value = new Zend_Measure_Lightness('string -100.100,200',Zend_Measure_Lightness::STANDARD,'de');
        $serial = $value->serialize();
        $this->assertTrue(!empty($serial),'Zend_Measure_Lightness not serialized');
    }


    /**
     * test for unserialization
     * expected object
     */
    public function testLightnessUnSerialize()
    {
        $value = new Zend_Measure_Lightness('string -100.100,200',Zend_Measure_Lightness::STANDARD,'de');
        $serial = $value->serialize();
        $newvalue = unserialize($serial);
        $this->assertTrue($value->equals($newvalue),'Zend_Measure_Lightness not unserialized');
    }


    /**
     * test for set positive value
     * expected integer
     */
    public function testLightnessSetPositive()
    {
        $value = new Zend_Measure_Lightness('100',Zend_Measure_Lightness::STANDARD,'de');
        $value->setValue('200',Zend_Measure_Lightness::STANDARD,'de');
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Lightness value expected to be a positive integer');
    }


    /**
     * test for set negative value
     * expected integer
     */
    public function testLightnessSetNegative()
    {
        $value = new Zend_Measure_Lightness('-100',Zend_Measure_Lightness::STANDARD,'de');
        $value->setValue('-200',Zend_Measure_Lightness::STANDARD,'de');
        $this->assertEquals(-200, $value->getValue(), 'Zend_Measure_Lightness value expected to be a negative integer');
    }


    /**
     * test for set decimal value
     * expected float
     */
    public function testLightnessSetDecimal()
    {
        $value = new Zend_Measure_Lightness('-100,200',Zend_Measure_Lightness::STANDARD,'de');
        $value->setValue('-200,200',Zend_Measure_Lightness::STANDARD,'de');
        $this->assertEquals(-200.200, $value->getValue(), 'Zend_Measure_Lightness value expected to be a decimal value');
    }


    /**
     * test for set decimal seperated value
     * expected float
     */
    public function testLightnessSetDecimalSeperated()
    {
        $value = new Zend_Measure_Lightness('-100.100,200',Zend_Measure_Lightness::STANDARD,'de');
        $value->setValue('-200.200,200',Zend_Measure_Lightness::STANDARD,'de');
        $this->assertEquals(-200200.200, $value->getValue(),'Zend_Measure_Lightness Object not returned');
    }


    /**
     * test for set string with integrated value
     * expected float
     */
    public function testLightnessSetString()
    {
        $value = new Zend_Measure_Lightness('string -100.100,200',Zend_Measure_Lightness::STANDARD,'de');
        $value->setValue('otherstring -200.200,200',Zend_Measure_Lightness::STANDARD,'de');
        $this->assertEquals(-200200.200, $value->getValue(),'Zend_Measure_Lightness Object not returned');
    }


    /**
     * test for exception unknown type
     * expected exception
     */
    public function testLightnessSetUnknownType()
    {
        try {
            $value = new Zend_Measure_Lightness('100',Zend_Measure_Lightness::STANDARD,'de');
            $value->setValue('otherstring -200.200,200','Lightness::UNKNOWN','de');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown value
     * expected exception
     */
    public function testLightnessSetUnknownValue()
    {
        try {
            $value = new Zend_Measure_Lightness('100',Zend_Measure_Lightness::STANDARD,'de');
            $value->setValue('novalue',Zend_Measure_Lightness::STANDARD,'de');
            $this->assertTrue(false,'Exception expected because of empty value');
        } catch (Exception $e) {
            return; // Test OK
        }
    }


    /**
     * test for exception unknown locale
     * expected exception
     */
    public function testLightnessSetUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Lightness('100',Zend_Measure_Lightness::STANDARD,'de');
            $value->setValue('200',Zend_Measure_Lightness::STANDARD,'nolocale');
            $this->assertTrue(false,'Exception expected because of unknown locale');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test setting type
     * expected new type
     */
    public function testLightnessSetType()
    {
        $value = new Zend_Measure_Lightness('-100',Zend_Measure_Lightness::STANDARD,'de');
        $value->setType(Zend_Measure_Lightness::STILB);
        $this->assertEquals($value->getType(), Zend_Measure_Lightness::STILB, 'Zend_Measure_Lightness type expected');
    }


    /**
     * test setting unknown type
     * expected new type
     */
    public function testLightnessSetTypeFailed()
    {
        try {
            $value = new Zend_Measure_Lightness('-100',Zend_Measure_Lightness::STANDARD,'de');
            $value->setType('Lightness::UNKNOWN');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // OK
        }
    }


    /**
     * test toString
     * expected string
     */
    public function testLightnessToString()
    {
        $value = new Zend_Measure_Lightness('-100',Zend_Measure_Lightness::STANDARD,'de');
        $this->assertEquals($value->toString(), '-100 cd/m�', 'Value -100 cd/m� expected');
    }


    /**
     * test __toString
     * expected string
     */
    public function testLightness_ToString()
    {
        $value = new Zend_Measure_Lightness('-100',Zend_Measure_Lightness::STANDARD,'de');
        $this->assertEquals($value->__toString(), '-100 cd/m�', 'Value -100 cd/m� expected');
    }


    /**
     * test getConversionList
     * expected array
     */
    public function testLightnessConversionList()
    {
        $value = new Zend_Measure_Lightness('-100',Zend_Measure_Lightness::STANDARD,'de');
        $unit  = $value->getConversionList();
        $this->assertTrue(is_array($unit), 'Array expected');
    }
}
