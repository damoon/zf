<?php
/**
 * @package    Zend_Measure
 * @subpackage UnitTests
 */


/**
 * Zend_Measure_Number
 */
require_once 'Zend/Measure/Number.php';

/**
 * PHPUnit test case
 */
require_once 'PHPUnit/Framework/TestCase.php';


/**
 * @package    Zend_Measure
 * @subpackage UnitTests
 */
class Zend_Measure_NumberTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
    }


    /**
     * test for Number initialisation
     * expected instance
     */
    public function testNumberInit()
    {
        $value = new Zend_Measure_Number('100',Zend_Measure_Number::STANDARD,'de');
        $this->assertTrue($value instanceof Zend_Measure_Number,'Zend_Measure_Number Object not returned');
    }


    /**
     * test for exception unknown type
     * expected exception
     */
    public function testNumberUnknownType()
    {
        try {
            $value = new Zend_Measure_Number('100','Number::UNKNOWN','de');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown value
     * expected exception
     */
    public function testNumberUnknownValue()
    {
        try {
            $value = new Zend_Measure_Number('novalue',Zend_Measure_Number::STANDARD,'de');
            $this->assertTrue(false,'Exception expected because of empty value');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown locale
     * expected root value
     */
    public function testNumberUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Number('100',Zend_Measure_Number::STANDARD,'nolocale');
            $this->assertTrue(false,'Exception expected because of unknown locale');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for standard locale
     * expected integer
     */
    public function testNumberNoLocale()
    {
        $value = new Zend_Measure_Number('100',Zend_Measure_Number::STANDARD);
        $this->assertEquals(100, $value->getValue(),'Zend_Measure_Number value expected');
    }


    /**
     * test for positive value
     * expected integer
     */
    public function testNumberValuePositive()
    {
        $value = new Zend_Measure_Number('100',Zend_Measure_Number::STANDARD,'de');
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Number value expected to be a positive integer');
    }


    /**
     * test for negative value
     * expected integer
     */
    public function testNumberValueNegative()
    {
        $value = new Zend_Measure_Number('-100',Zend_Measure_Number::STANDARD,'de');
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Number value expected to be a negative integer');
    }


    /**
     * test for decimal value
     * expected float
     */
    public function testNumberValueDecimal()
    {
        $value = new Zend_Measure_Number('-100,200',Zend_Measure_Number::STANDARD,'de');
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Number value expected to be a decimal value');
    }


    /**
     * test for decimal seperated value
     * expected float
     */
    public function testNumberValueDecimalSeperated()
    {
        $value = new Zend_Measure_Number('-100.100,200',Zend_Measure_Number::STANDARD,'de');
        $this->assertEquals(100100, $value->getValue(),'Zend_Measure_Number Object not returned');
    }


    /**
     * test for string with integrated value
     * expected float
     */
    public function testNumberValueString()
    {
        $value = new Zend_Measure_Number('string -100.100,200',Zend_Measure_Number::STANDARD,'de');
        $this->assertEquals(100100, $value->getValue(),'Zend_Measure_Number Object not returned');
    }


    /**
     * test for equality
     * expected true
     */
    public function testNumberEquality()
    {
        $value = new Zend_Measure_Number('string -100.100,200',Zend_Measure_Number::STANDARD,'de');
        $newvalue = new Zend_Measure_Number('otherstring -100.100,200',Zend_Measure_Number::STANDARD,'de');
        $this->assertTrue($value->equals($newvalue),'Zend_Measure_Number Object should be equal');
    }


    /**
     * test for no equality
     * expected false
     */
    public function testNumberNoEquality()
    {
        $value = new Zend_Measure_Number('string -100.100,200',Zend_Measure_Number::STANDARD,'de');
        $newvalue = new Zend_Measure_Number('otherstring -100,200',Zend_Measure_Number::STANDARD,'de');
        $this->assertFalse($value->equals($newvalue),'Zend_Measure_Number Object should be not equal');
    }


    /**
     * test for serialization
     * expected string
     */
    public function testNumberSerialize()
    {
        $value = new Zend_Measure_Number('string -100.100,200',Zend_Measure_Number::STANDARD,'de');
        $serial = $value->serialize();
        $this->assertTrue(!empty($serial),'Zend_Measure_Number not serialized');
    }


    /**
     * test for unserialization
     * expected object
     */
    public function testNumberUnSerialize()
    {
        $value = new Zend_Measure_Number('string -100.100,200',Zend_Measure_Number::STANDARD,'de');
        $serial = $value->serialize();
        $newvalue = unserialize($serial);
        $this->assertTrue($value->equals($newvalue),'Zend_Measure_Number not unserialized');
    }


    /**
     * test for set positive value
     * expected integer
     */
    public function testNumberSetPositive()
    {
        $value = new Zend_Measure_Number('100',Zend_Measure_Number::STANDARD,'de');
        $value->setValue('200',Zend_Measure_Number::STANDARD,'de');
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Number value expected to be a positive integer');
    }


    /**
     * test for set negative value
     * expected integer
     */
    public function testNumberSetNegative()
    {
        $value = new Zend_Measure_Number('-100',Zend_Measure_Number::STANDARD,'de');
        $value->setValue('-200',Zend_Measure_Number::STANDARD,'de');
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Number value expected to be a negative integer');
    }


    /**
     * test for set decimal value
     * expected float
     */
    public function testNumberSetDecimal()
    {
        $value = new Zend_Measure_Number('-100,200',Zend_Measure_Number::STANDARD,'de');
        $value->setValue('-200,200',Zend_Measure_Number::STANDARD,'de');
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Number value expected to be a decimal value');
    }


    /**
     * test for set decimal seperated value
     * expected float
     */
    public function testNumberSetDecimalSeperated()
    {
        $value = new Zend_Measure_Number('-100.100,200',Zend_Measure_Number::STANDARD,'de');
        $value->setValue('-200.200,200',Zend_Measure_Number::STANDARD,'de');
        $this->assertEquals(200200, $value->getValue(),'Zend_Measure_Number Object not returned');
    }


    /**
     * test for set string with integrated value
     * expected float
     */
    public function testNumberSetString()
    {
        $value = new Zend_Measure_Number('string -100.100,200',Zend_Measure_Number::STANDARD,'de');
        $value->setValue('otherstring -200.200,200',Zend_Measure_Number::STANDARD,'de');
        $this->assertEquals(200200, $value->getValue(),'Zend_Measure_Number Object not returned');
    }


    /**
     * test for exception unknown type
     * expected exception
     */
    public function testNumberSetUnknownType()
    {
        try {
            $value = new Zend_Measure_Number('100',Zend_Measure_Number::STANDARD,'de');
            $value->setValue('otherstring -200.200,200','Number::UNKNOWN','de');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown value
     * expected exception
     */
    public function testNumberSetUnknownValue()
    {
        try {
            $value = new Zend_Measure_Number('100',Zend_Measure_Number::STANDARD,'de');
            $value->setValue('novalue',Zend_Measure_Number::STANDARD,'de');
            $this->assertTrue(false,'Exception expected because of empty value');
        } catch (Exception $e) {
            return; // Test OK
        }
    }


    /**
     * test for exception unknown locale
     * expected exception
     */
    public function testNumberSetUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Number('100',Zend_Measure_Number::STANDARD,'de');
            $value->setValue('200',Zend_Measure_Number::STANDARD,'nolocale');
            $this->assertTrue(false,'Exception expected because of unknown locale');
        } catch (Exception $e) {
            return true; // Test OK
        }
    }


    /**
     * test for exception unknown locale
     * expected exception
     */
    public function testNumberSetWithNoLocale()
    {
        $value = new Zend_Measure_Number('100', Zend_Measure_Number::STANDARD, 'de');
        $value->setValue('200', Zend_Measure_Number::STANDARD);
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Number value expected to be a positive integer');
    }


    /**
     * test setting type
     * expected new type
     */
    public function testNumberSetType()
    {
        $value = new Zend_Measure_Number('-100',Zend_Measure_Number::STANDARD,'de');
        $value->setType(Zend_Measure_Number::BINARY);
        $this->assertEquals($value->getType(), Zend_Measure_Number::BINARY, 'Zend_Measure_Number type expected');
    }


    /**
     * test setting binary type
     * expected new type
     */
    public function testNumberSetFromBinary()
    {
        $value = new Zend_Measure_Number('100101',Zend_Measure_Number::BINARY,'de');
        $value->setType(Zend_Measure_Number::ROMAN);
        $this->assertEquals($value->getType(), Zend_Measure_Number::ROMAN, 'Zend_Measure_Number type expected');
    }


    /**
     * test setting corrupted binary type
     * expected new type
     */
    public function testNumberSetFromBinaryFalse()
    {
        $value = new Zend_Measure_Number('1001020',Zend_Measure_Number::BINARY,'de');
        $value->setType(Zend_Measure_Number::HEXADECIMAL);
        $this->assertEquals($value->getType(), Zend_Measure_Number::HEXADECIMAL, 'Zend_Measure_Number type expected');
    }


    /**
     * test setting roman type
     * expected new type
     */
    public function testNumberSetFromRoman()
    {
        $value = new Zend_Measure_Number('MCXVII',Zend_Measure_Number::ROMAN,'de');
        $value->setType(Zend_Measure_Number::HEXADECIMAL);
        $this->assertEquals($value->getType(), Zend_Measure_Number::HEXADECIMAL, 'Zend_Measure_Number type expected');
    }


    /**
     * test setting roman type
     * expected new type
     */
    public function testNumberSetFromTernary()
    {
        $value = new Zend_Measure_Number('102122',Zend_Measure_Number::TERNARY,'de');
        $value->setType(Zend_Measure_Number::OCTAL);
        $this->assertEquals($value->getType(), Zend_Measure_Number::OCTAL, 'Zend_Measure_Number type expected');
    }


    /**
     * test setting roman type
     * expected new type
     */
    public function testNumberSetFromQuintal()
    {
        $value = new Zend_Measure_Number('1032302',Zend_Measure_Number::QUINTAL,'de');
        $value->setType(Zend_Measure_Number::TERNARY);
        $this->assertEquals($value->getType(), Zend_Measure_Number::TERNARY, 'Zend_Measure_Number type expected');
    }


    /**
     * test setting roman type
     * expected new type
     */
    public function testNumberSetFromHex()
    {
        $value = new Zend_Measure_Number('1234ACE',Zend_Measure_Number::HEXADECIMAL,'de');
        $value->setType(Zend_Measure_Number::TERNARY);
        $this->assertEquals($value->getType(), Zend_Measure_Number::TERNARY, 'Zend_Measure_Number type expected');
    }


    /**
     * test setting roman type
     * expected new type
     */
    public function testNumberSetFromOctal()
    {
        $value = new Zend_Measure_Number('1234075',Zend_Measure_Number::OCTAL,'de');
        $value->setType(Zend_Measure_Number::TERNARY);
        $this->assertEquals($value->getType(), Zend_Measure_Number::TERNARY, 'Zend_Measure_Number type expected');
    }


    /**
     * test setting unknown type
     * expected new type
     */
    public function testNumberSetTypeFailed()
    {
        try {
            $value = new Zend_Measure_Number('-100',Zend_Measure_Number::STANDARD,'de');
            $value->setType('Number::UNKNOWN');
            $this->assertTrue(false,'Exception expected because of unknown type');
        } catch (Exception $e) {
            return true; // OK
        }
    }


    /**
     * test toString
     * expected string
     */
    public function testNumberToString()
    {
        $value = new Zend_Measure_Number('-100',Zend_Measure_Number::STANDARD,'de');
        $this->assertEquals($value->toString(), '100', 'Value 100 expected');
    }


    /**
     * test __toString
     * expected string
     */
    public function testNumber_ToString()
    {
        $value = new Zend_Measure_Number('-100',Zend_Measure_Number::STANDARD,'de');
        $this->assertEquals($value->__toString(), '100', 'Value 100 expected');
    }


    /**
     * test getConversionList
     * expected array
     */
    public function testNumberConversionList()
    {
        $value = new Zend_Measure_Number('-100',Zend_Measure_Number::STANDARD,'de');
        $unit  = $value->getConversionList();
        $this->assertTrue(is_array($unit), 'Array expected');
    }
}
