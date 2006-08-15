<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Measure
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


/**
 * Implement basic abstract class
 */
require_once 'Zend/Measure/Abstract.php';

/**
 * Implement Locale Data and Format class
 */
require_once 'Zend/Locale/Data.php';
require_once 'Zend/Locale/Format.php';


/**
 * @category   Zend
 * @package    Zend_Measure
 * @subpackage Zend_Measure_Number
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * This class can only handle numbers without precission
 */
class Zend_Measure_Number extends Zend_Measure_Abstract
{
    // Number definitions
    const STANDARD = 'Number::DECIMAL';

    const BINARY           = 'Number::BINARY';
    const TERNARY          = 'Number::TERNARY';
    const QUINTAL          = 'Number::QUINTAL';
    const OCTAL            = 'Number::OCTAL';
    const DECIMAL          = 'Number::DECIMAL';
    const HEXADECIMAL      = 'Number::HEXADECIMAL';
    const ROMAN            = 'Number::ROMAN';

    private static $_UNITS = array(
        'Number::BINARY'           => array(2,''),
        // Todo: Unit Sign: SUB 2
        'Number::TERNARY'          => array(3,''),
        // Todo: Unit Sign: SUB 3
        'Number::QUINTAL'          => array(4,''),
        // Todo: Unit Sign: SUB 4
        'Number::OCTAL'            => array(8,''),
        // Todo: Unit Sign: SUB 8
        'Number::DECIMAL'          => array(10,''),
        // Todo: Unit Sign SUB 10
        'Number::HEXADECIMAL'      => array(16,''),
        // Todo: Unit Sign SUB 16
        'Number::ROMAN'            => array(99,'')
    );


    // Definition of all roman signs
    private static $_ROMAN = array(
        'I' => 1,
        'A' => 4,
        'V' => 5,
        'B' => 9,
        'X' => 10,
        'E' => 40,
        'L' => 50,
        'F' => 90,
        'C' => 100,
        'G' => 400,
        'D' => 500,
        'H' => 900,
        'M' => 1000,
        'J' => 4000,
        'P' => 5000,
        'K' => 9000,
        'Q' => 10000,
        'N' => 40000,
        'R' => 50000,
        'W' => 90000,
        'S' => 100000,
        'Y' => 400000,
        'T' => 500000,
        'Z' => 900000,
        'U' => 1000000
    );


    // Convertion table for roman signs
    private static $_ROMANCONVERT = array(
        '/_V/' => '/P/',
        '/_X/' => '/Q/',
        '/_L/' => '/R/',
        '/_C/' => '/S/',
        '/_D/' => '/T/',
        '/_M/' => '/U/',
        '/IV/' => '/A/',
        '/IX/' => '/B/',
        '/XL/' => '/E/',
        '/XC/' => '/F/',
        '/CD/' => '/G/',
        '/CM/' => '/H/',
        '/M_V/'=> '/J/',
        '/MQ/' => '/K/',
        '/QR/' => '/N/',
        '/QS/' => '/W/',
        '/ST/' => '/Y/',
        '/SU/' => '/Z/'
    );

    /**
     * Zend_Measure_Number provides an locale aware class for
     * conversion and formatting of number values
     *
     * Zend_Measure $input can be a locale based input string
     * or a value. $locale can be used to define that the
     * input is made in a different language than the actual one.
     *
     * @param  $value  mixed  - Value as string, integer, real or float
     * @param  $type   type   - OPTIONAL a Zend_Measure_Number Type
     * @param  $locale locale - OPTIONAL a Zend_Locale Type
     * @throws Zend_Measure_Exception
     */
    public function __construct($value, $type, $locale)
    {
        $this->setValue($value, $type, $locale);
    }


    /**
     * Compare if the value and type is equal
     *
     * @return boolean
     */
    public function equals($object)
    {
        if ($object->toString() == $this->toString())
        {
            return true;
        }
        return false;
    }


    /**
     * Set a new value
     *
     * @param  $value  mixed  - Value as string, integer, real or float
     * @param  $type   type   - OPTIONAL a Zend_Measure_Number Type
     * @param  $locale locale - OPTIONAL a Zend_Locale Type
     * @throws Zend_Measure_Exception
     */
    public function setValue($value, $type, $locale)
    {
        if (empty(self::$_UNITS[$type]))
            self::throwException('unknown type of number:'.$type);

        switch($type) {
            case 'Number::BINARY' :
                preg_match('/[01]+/',$value,$ergebnis);
                $value = $ergebnis[0];
                break;
            case 'Number::TERNARY' :
                preg_match('/[012]+/',$value,$ergebnis);
                $value = $ergebnis[0];
                break;
            case 'Number::QUINTAL' :
                preg_match('/[0123]+/',$value,$ergebnis);
                $value = $ergebnis[0];
                break;
            case 'Number::OCTAL' :
                preg_match('/[01234567]+/',$value,$ergebnis);
                $value = $ergebnis[0];
                break;
            case 'Number::HEXADECIMAL' :
                preg_match('/[0123456789ABCDEF]+/',strtoupper($value),$ergebnis);
                $value = $ergebnis[0];
                break;
            case 'Number::ROMAN' :
                preg_match('/[IVXLCDM_]+/',strtoupper($value),$ergebnis);
                $value = $ergebnis[0];
                break;
            default:
                $value = Zend_Locale_Format::getNumber($value, $locale);
                if (bccomp($value,0) < 0)
                  $value =  bcsqrt(bcpow($value,2));
                break;
        }

        parent::setValue($value);
        parent::setType($type);
    }


    /**
     * Convert input to decimal value string
     *
     * @param $input mixed  - input string
     * @param $type  type   - type from which to convert to decimal
     */
    private function toDecimal($input, $type)
    {
        $value = "";
        // Convert base xx values
        if (self::$_UNITS[$type][0] <= 16)
        {
            $split = str_split($input);
            $length = strlen($input);
            for($X = 0; $X < $length; ++$X)
            {
                $split[$X] = hexdec($split[$X]);
                $value = bcadd($value, bcmul($split[$X], bcpow(self::$_UNITS[$type][0], ($length - $X - 1))));
            }
        }

        // Convert roman numbers
        if ($type == 'Number::ROMAN') {
            $input = strtoupper($input);
            $input = preg_replace(array_keys(self::$_ROMANCONVERT),array_values(self::$_ROMANCONVERT), $input);

            $split = preg_split('//',strrev($input), -1, PREG_SPLIT_NO_EMPTY);
            for ($X=0; $X < sizeof($split); $X++)
            {
                $num = self::$_ROMAN[$split[$X]];
                if ($X > 0 && ($num < self::$_ROMAN[$split[$X-1]]))
                    $num -= $num;
                $value += $num;
            }
            str_replace('/','',$value);
        }
        return $value;
    }


    /**
     * Convert input to type value string
     *
     * @param $input mixed  - input string
     * @param $type  type   - type to convert to
     */
    private function fromDecimal($value, $type)
    {
        if (self::$_UNITS[$type][0] <= 16)
        {
            $newvalue = "";
            while(bccomp($value,0) != 0)
            {
                $target = bcmod($value,self::$_UNITS[$type][0]);
                $target = strtoupper(dechex($target));
                $newvalue = $target.$newvalue;
                $value = bcdiv($value,self::$_UNITS[$type][0],0);
            }
        }

        if ($type == 'Number::ROMAN') {
            $i = 0;
            $newvalue = "";
            $romanval = array_values(array_reverse(self::$_ROMAN));
            $romankey = array_keys(array_reverse(self::$_ROMAN));
            while(bccomp($value,0) != 0)
            {
                while ($value >= $romanval[$i])
                { 
                    $value   -= $romanval[$i];
                    $newvalue .= $romankey[$i]; 
                } 
                $i++; 
            }

            $newvalue = str_replace("/","",preg_replace(array_values(self::$_ROMANCONVERT),array_keys(self::$_ROMANCONVERT), $newvalue)); 
        }

        return $newvalue;
    }

    /**
     * Set a new type, and convert the value
     *
     * @throws Zend_Measure_Exception
     */
    public function setType($type)
    {
        if (empty(self::$_UNITS[$type]))
            self::throwException('unknown type of number:'.$type);

        $value = $this->toDecimal(parent::getValue(), parent::getType());
        $value = $this->fromDecimal($value, $type);

        parent::setValue($value);
        parent::setType($type);
    }


    /**
     * Returns a string representation
     *
     * @return string
     */
    public function toString()
    {
        return parent::getValue();
    }


    /**
     * Returns a string representation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }


    /**
     * Returns the conversion list
     */
    public function getConversionList()
    {
        return self::$_UNITS;
    }
}