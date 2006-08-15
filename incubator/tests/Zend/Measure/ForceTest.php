<?php
/**
 * @package    Zend_Measure
 * @subpackage UnitTests
 */


/**
 * Zend_Measure_Force
 */
require_once 'Zend/Measure/Force.php';

/**
 * PHPUnit2 test case
 */
require_once 'PHPUnit2/Framework/TestCase.php';


/**
 * @package    Zend_Measure
 * @subpackage UnitTests
 */
class Zend_Measure_ForceTest extends PHPUnit2_Framework_TestCase
{

    public function setUp()
    {
    }


    /**
     * test for Force initialisation
     * expected instance
     */
    public function testForceInit()
    {
        $value = new Zend_Measure_Force('100',Zend_Measure_Force::STANDARD,'de');
        $this->assertTrue($value instanceof Zend_Measure_Force,'Zend_Measure_Force Object not returned');
    }


    /**
     * test for exception unknown type
     * expected exception
     */
    public function testForceUnknownType()
    {
        try {
            $value = new Zend_Measure_Force('100','Force::UNKNOWN','de');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown value
     * expected exception
     */
    public function testForceUnknownValue()
    {
        try {
            $value = new Zend_Measure_Force('novalue',Zend_Measure_Force::STANDARD,'de');
            $this->assertTrue(false,'Exception expected because of empty value');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown locale
     * expected root value
     */
    public function testForceUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Force('100',Zend_Measure_Force::STANDARD,'nolocale');
            $this->assertTrue(false,'Exception expected because of unknown locale');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for positive value
     * expected integer
     */
    public function testForceValuePositive()
    {
        $value = new Zend_Measure_Force('100',Zend_Measure_Force::STANDARD,'de');
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Force value expected to be a positive integer');
    }


    /**
     * test for negative value
     * expected integer
     */
    public function testForceValueNegative()
    {
        $value = new Zend_Measure_Force('-100',Zend_Measure_Force::STANDARD,'de');
        $this->assertEquals(-100, $value->getValue(), 'Zend_Measure_Force value expected to be a negative integer');
    }


    /**
     * test for decimal value
     * expected float
     */
    public function testForceValueDecimal()
    {
        $value = new Zend_Measure_Force('-100,200',Zend_Measure_Force::STANDARD,'de');
        $this->assertEquals(-100.200, $value->getValue(), 'Zend_Measure_Force value expected to be a decimal value');
    }


    /**
     * test for decimal seperated value
     * expected float
     */
    public function testForceValueDecimalSeperated()
    {
        $value = new Zend_Measure_Force('-100.100,200',Zend_Measure_Force::STANDARD,'de');
        $this->assertEquals(-100100.200, $value->getValue(),'Zend_Measure_Force Object not returned');
    }


    /**
     * test for string with integrated value
     * expected float
     */
    public function testForceValueString()
    {
        $value = new Zend_Measure_Force('string -100.100,200',Zend_Measure_Force::STANDARD,'de');
        $this->assertEquals(-100100.200, $value->getValue(),'Zend_Measure_Force Object not returned');
    }


    /**
     * test for equality
     * expected true
     */
    public function testForceEquality()
    {
        $value = new Zend_Measure_Force('string -100.100,200',Zend_Measure_Force::STANDARD,'de');
        $newvalue = new Zend_Measure_Force('otherstring -100.100,200',Zend_Measure_Force::STANDARD,'de');
        $this->assertTrue($value->equals($newvalue),'Zend_Measure_Force Object should be equal');
    }


    /**
     * test for no equality
     * expected false
     */
    public function testForceNoEquality()
    {
        $value = new Zend_Measure_Force('string -100.100,200',Zend_Measure_Force::STANDARD,'de');
        $newvalue = new Zend_Measure_Force('otherstring -100,200',Zend_Measure_Force::STANDARD,'de');
        $this->assertFalse($value->equals($newvalue),'Zend_Measure_Force Object should be not equal');
    }


    /**
     * test for serialization
     * expected string
     */
    public function testForceSerialize()
    {
        $value = new Zend_Measure_Force('string -100.100,200',Zend_Measure_Force::STANDARD,'de');
        $serial = $value->serialize();
        $this->assertTrue(!empty($serial),'Zend_Measure_Force not serialized');
    }


    /**
     * test for unserialization
     * expected object
     */
    public function testForceUnSerialize()
    {
        $value = new Zend_Measure_Force('string -100.100,200',Zend_Measure_Force::STANDARD,'de');
        $serial = $value->serialize();
        $newvalue = unserialize($serial);
        $this->assertTrue($value->equals($newvalue),'Zend_Measure_Force not unserialized');
    }


    /**
     * test for set positive value
     * expected integer
     */
    public function testForceSetPositive()
    {
        $value = new Zend_Measure_Force('100',Zend_Measure_Force::STANDARD,'de');
        $value->setValue('200',Zend_Measure_Force::STANDARD,'de');
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Force value expected to be a positive integer');
    }


    /**
     * test for set negative value
     * expected integer
     */
    public function testForceSetNegative()
    {
        $value = new Zend_Measure_Force('-100',Zend_Measure_Force::STANDARD,'de');
        $value->setValue('-200',Zend_Measure_Force::STANDARD,'de');
        $this->assertEquals(-200, $value->getValue(), 'Zend_Measure_Force value expected to be a negative integer');
    }


    /**
     * test for set decimal value
     * expected float
     */
    public function testForceSetDecimal()
    {
        $value = new Zend_Measure_Force('-100,200',Zend_Measure_Force::STANDARD,'de');
        $value->setValue('-200,200',Zend_Measure_Force::STANDARD,'de');
        $this->assertEquals(-200.200, $value->getValue(), 'Zend_Measure_Force value expected to be a decimal value');
    }


    /**
     * test for set decimal seperated value
     * expected float
     */
    public function testForceSetDecimalSeperated()
    {
        $value = new Zend_Measure_Force('-100.100,200',Zend_Measure_Force::STANDARD,'de');
        $value->setValue('-200.200,200',Zend_Measure_Force::STANDARD,'de');
        $this->assertEquals(-200200.200, $value->getValue(),'Zend_Measure_Force Object not returned');
    }


    /**
     * test for set string with integrated value
     * expected float
     */
    public function testForceSetString()
    {
        $value = new Zend_Measure_Force('string -100.100,200',Zend_Measure_Force::STANDARD,'de');
        $value->setValue('otherstring -200.200,200',Zend_Measure_Force::STANDARD,'de');
        $this->assertEquals(-200200.200, $value->getValue(),'Zend_Measure_Force Object not returned');
    }


    /**
     * test for exception unknown type
     * expected exception
     */
    public function testForceSetUnknownType()
    {
        try {
            $value = new Zend_Measure_Force('100',Zend_Measure_Force::STANDARD,'de');
            $value->setValue('otherstring -200.200,200','Force::UNKNOWN','de');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown value
     * expected exception
     */
    public function testForceSetUnknownValue()
    {
        try {
            $value = new Zend_Measure_Force('100',Zend_Measure_Force::STANDARD,'de');
            $value->setValue('novalue',Zend_Measure_Force::STANDARD,'de');
            $this->assertTrue(false,'Exception expected because of empty value');
        } catch (Exception $e) {
            return; // Test OK
        }
    }


    /**
     * test for exception unknown locale
     * expected exception
     */
    public function testForceSetUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Force('100',Zend_Measure_Force::STANDARD,'de');
            $value->setValue('200',Zend_Measure_Force::STANDARD,'nolocale');
            $this->assertTrue(false,'Exception expected because of unknown locale');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test setting type
     * expected new type
     */
    public function testForceSetType()
    {
        $value = new Zend_Measure_Force('-100',Zend_Measure_Force::STANDARD,'de');
        $value->setType(Zend_Measure_Force::NANONEWTON);
        $this->assertEquals($value->getType(), Zend_Measure_Force::NANONEWTON, 'Zend_Measure_Force type expected');
    }


    /**
     * test setting unknown type
     * expected new type
     */
    public function testForceSetTypeFailed()
    {
        try {
            $value = new Zend_Measure_Force('-100',Zend_Measure_Force::STANDARD,'de');
            $value->setType('Force::UNKNOWN');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // OK
        }
    }


    /**
     * test toString
     * expected string
     */
    public function testForceToString()
    {
        $value = new Zend_Measure_Force('-100',Zend_Measure_Force::STANDARD,'de');
        $this->assertEquals($value->toString(), '-100 N', 'Value -100 N expected');
    }


    /**
     * test __toString
     * expected string
     */
    public function testForce_ToString()
    {
        $value = new Zend_Measure_Force('-100',Zend_Measure_Force::STANDARD,'de');
        $this->assertEquals($value->__toString(), '-100 N', 'Value -100 N expected');
    }


    /**
     * test getConversionList
     * expected array
     */
    public function testForceConversionList()
    {
        $value = new Zend_Measure_Force('-100',Zend_Measure_Force::STANDARD,'de');
        $unit  = $value->getConversionList();
        $this->assertTrue(is_array($unit), 'Array expected');
    }
}
