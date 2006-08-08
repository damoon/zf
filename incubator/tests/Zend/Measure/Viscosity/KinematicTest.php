<?php
/**
 * @package    Zend_Measure
 * @subpackage UnitTests
 */


/**
 * Zend_Measure_Viscosity_Kinematic
 */
require_once 'Zend/Measure/Viscosity/Kinematic.php';

/**
 * PHPUnit2 test case
 */
require_once 'PHPUnit2/Framework/TestCase.php';


/**
 * @package    Zend_Measure
 * @subpackage UnitTests
 */
class Zend_Measure_Viscosity_KinematicTest extends PHPUnit2_Framework_TestCase
{

    public function setUp()
    {
    }


    /**
     * test for Mass initialisation
     * expected instance
     */
    public function testMassInit()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('100',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $this->assertTrue($value instanceof Zend_Measure_Viscosity_Kinematic,'Zend_Measure_Viscosity_Kinematic Object not returned');
    }


    /**
     * test for exception unknown type
     * expected exception
     */
    public function testViscosity_KinematicUnknownType()
    {
        try {
            $value = new Zend_Measure_Viscosity_Kinematic('100','Viscosity_Kinematic::UNKNOWN','de');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown value
     * expected exception
     */
    public function testViscosity_KinematicUnknownValue()
    {
        try {
            $value = new Zend_Measure_Viscosity_Kinematic('novalue',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
            $this->assertTrue(false,'Exception expected because of empty value');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown locale
     * expected root value
     */
    public function testViscosity_KinematicUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Viscosity_Kinematic('100',Zend_Measure_Viscosity_Kinematic::STANDARD,'nolocale');
            $this->assertTrue(false,'Exception expected because of unknown locale');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for positive value
     * expected integer
     */
    public function testViscosity_KinematicValuePositive()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('100',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic value expected to be a positive integer');
    }


    /**
     * test for negative value
     * expected integer
     */
    public function testViscosity_KinematicValueNegative()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $this->assertEquals(-100, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic value expected to be a negative integer');
    }


    /**
     * test for decimal value
     * expected float
     */
    public function testViscosity_KinematicValueDecimal()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100,200',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $this->assertEquals(-100.200, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic value expected to be a decimal value');
    }


    /**
     * test for decimal seperated value
     * expected float
     */
    public function testViscosity_KinematicValueDecimalSeperated()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100.100,200',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $this->assertEquals(-100100.200, $value->getValue(),'Zend_Measure_Viscosity_Kinematic Object not returned');
    }


    /**
     * test for string with integrated value
     * expected float
     */
    public function testViscosity_KinematicValueString()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('string -100.100,200',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $this->assertEquals(-100100.200, $value->getValue(),'Zend_Measure_Viscosity_Kinematic Object not returned');
    }


    /**
     * test for equality
     * expected true
     */
    public function testViscosity_KinematicEquality()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('string -100.100,200',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $newvalue = new Zend_Measure_Viscosity_Kinematic('otherstring -100.100,200',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $this->assertTrue($value->equals($newvalue),'Zend_Measure_Viscosity_Kinematic Object should be equal');
    }


    /**
     * test for no equality
     * expected false
     */
    public function testViscosity_KinematicNoEquality()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('string -100.100,200',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $newvalue = new Zend_Measure_Viscosity_Kinematic('otherstring -100,200',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $this->assertFalse($value->equals($newvalue),'Zend_Measure_Viscosity_Kinematic Object should be not equal');
    }


    /**
     * test for serialization
     * expected string
     */
    public function testViscosity_KinematicSerialize()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('string -100.100,200',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $serial = $value->serialize();
        $this->assertTrue(!empty($serial),'Zend_Measure_Viscosity_Kinematic not serialized');
    }


    /**
     * test for unserialization
     * expected object
     */
    public function testViscosity_KinematicUnSerialize()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('string -100.100,200',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $serial = $value->serialize();
        $newvalue = unserialize($serial);
        $this->assertTrue($value->equals($newvalue),'Zend_Measure_Viscosity_Kinematic not unserialized');
    }


    /**
     * test for set positive value
     * expected integer
     */
    public function testViscosity_KinematicSetPositive()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('100',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $value->setValue('200',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic value expected to be a positive integer');
    }


    /**
     * test for set negative value
     * expected integer
     */
    public function testViscosity_KinematicSetNegative()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $value->setValue('-200',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $this->assertEquals(-200, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic value expected to be a negative integer');
    }


    /**
     * test for set decimal value
     * expected float
     */
    public function testViscosity_KinematicSetDecimal()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100,200',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $value->setValue('-200,200',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $this->assertEquals(-200.200, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic value expected to be a decimal value');
    }


    /**
     * test for set decimal seperated value
     * expected float
     */
    public function testViscosity_KinematicSetDecimalSeperated()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100.100,200',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $value->setValue('-200.200,200',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $this->assertEquals(-200200.200, $value->getValue(),'Zend_Measure_Viscosity_Kinematic Object not returned');
    }


    /**
     * test for set string with integrated value
     * expected float
     */
    public function testViscosity_KinematicSetString()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('string -100.100,200',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $value->setValue('otherstring -200.200,200',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $this->assertEquals(-200200.200, $value->getValue(),'Zend_Measure_Viscosity_Kinematic Object not returned');
    }


    /**
     * test for exception unknown type
     * expected exception
     */
    public function testViscosity_KinematicSetUnknownType()
    {
        try {
            $value = new Zend_Measure_Viscosity_Kinematic('100',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
            $value->setValue('otherstring -200.200,200','Viscosity_Kinematic::UNKNOWN','de');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown value
     * expected exception
     */
    public function testViscosity_KinematicSetUnknownValue()
    {
        try {
            $value = new Zend_Measure_Viscosity_Kinematic('100',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
            $value->setValue('novalue',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
            $this->assertTrue(false,'Exception expected because of empty value');
        } catch (Exception $e) {
            return; // Test OK
        }
    }


    /**
     * test for exception unknown locale
     * expected exception
     */
    public function testViscosity_KinematicSetUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Viscosity_Kinematic('100',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
            $value->setValue('200',Zend_Measure_Viscosity_Kinematic::STANDARD,'nolocale');
            $this->assertTrue(false,'Exception expected because of unknown locale');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test setting type
     * expected new type
     */
    public function testViscosity_KinematicSetType()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $value->setType(Zend_Measure_Viscosity_Kinematic::LENTOR);
        $this->assertEquals($value->getType(), Zend_Measure_Viscosity_Kinematic::LENTOR, 'Zend_Measure_Viscosity_Kinematic type expected');
    }


    /**
     * test setting computed type
     * expected new type
     */
    public function testViscosity_KinematicSetComputedType1()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $value->setType(Zend_Measure_Viscosity_Kinematic::LITER_PER_CENTIMETER_DAY);
        $this->assertEquals($value->getType(), Zend_Measure_Viscosity_Kinematic::LITER_PER_CENTIMETER_DAY, 'Zend_Measure_Viscosity_Kinematic type expected');
    }


    /**
     * test setting computed type
     * expected new type
     */
    public function testViscosity_KinematicSetComputedType2()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100',Zend_Measure_Viscosity_Kinematic::LITER_PER_CENTIMETER_DAY,'de');
        $value->setType(Zend_Measure_Viscosity_Kinematic::STANDARD);
        $this->assertEquals($value->getType(), Zend_Measure_Viscosity_Kinematic::STANDARD, 'Zend_Measure_Viscosity_Kinematic type expected');
    }


    /**
     * test setting unknown type
     * expected new type
     */
    public function testViscosity_KinematicSetTypeFailed()
    {
        try {
            $value = new Zend_Measure_Viscosity_Kinematic('-100',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
            $value->setType('Viscosity_Kinematic::UNKNOWN');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // OK
        }
    }


    /**
     * test toString
     * expected string
     */
    public function testViscosity_KinematicToString()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $this->assertEquals($value->toString(), '-100 m�/s', 'Value -100 m�/s expected');
    }


    /**
     * test __toString
     * expected string
     */
    public function testViscosity_Kinematic_ToString()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $this->assertEquals($value->__toString(), '-100 m�/s', 'Value -100 m�/s expected');
    }
}
