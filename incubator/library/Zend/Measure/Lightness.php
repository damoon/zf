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
require_once 'Zend/Locale.php';
require_once 'Zend/Locale/Data.php';
require_once 'Zend/Locale/Format.php';


/**
 * @category   Zend
 * @package    Zend_Measure
 * @subpackage Zend_Measure_Lightness
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Measure_Lightness extends Zend_Measure_Abstract
{
    // Lightness definitions
    const STANDARD               = 'Lightness::CANDELA_PER_SQUARE_METER';

    const APOSTILB                      = 'Lightness::APOSTILB';
    const BLONDEL                       = 'Lightness::BLONDEL';
    const CANDELA_PER_SQUARE_CENTIMETER = 'Lightness::CANDELA_PER_SQUARE_CENTIMETER';
    const CANDELA_PER_SQUARE_FOOT       = 'Lightness::CANDELA_PER_SQUARE_FOOT';
    const CANDELA_PER_SQUARE_INCH       = 'Lightness::CANDELA_PER_SQUARE_INCH';
    const CANDELA_PER_SQUARE_METER      = 'Lightness::CANDELA_PER_SQUARE_METER';
    const FOOTLAMBERT                   = 'Lightness::FOOTLAMBERT';
    const KILOCANDELA_PER_SQUARE_CENTIMETER = 'Lightness::KILOCANDELA_PER_SQUARE_CENTIMETER';
    const KILOCANDELA_PER_SQUARE_FOOT   = 'Lightness::KILOCANDELA_PER_SQUARE_FOOT';
    const KILOCANDELA_PER_SQUARE_INCH   = 'Lightness::KILOCANDELA_PER_SQUARE_INCH';
    const KILOCANDELA_PER_SQUARE_METER  = 'Lightness::KILOCANDELA_PER_SQUARE_METER';
    const LAMBERT                       = 'Lightness::LAMBERT';
    const MILLIMALBERT                  = 'Lightness::MILLILAMBERT';
    const NIT                           = 'Lightness::NIT';
    const STILB                         = 'Lightness::STILB';

    private static $_UNITS = array(
        'Lightness::APOSTILB'                      => array(0.31830989,'asb'),
        'Lightness::BLONDEL'                       => array(0.31830989,'blondel'),
        'Lightness::CANDELA_PER_SQUARE_CENTIMETER' => array(10000,'cd/cm�'),
        'Lightness::CANDELA_PER_SQUARE_FOOT'       => array(10.76391,'cd/ft�'),
        'Lightness::CANDELA_PER_SQUARE_INCH'       => array(1550.00304,'cd/in�'),
        'Lightness::CANDELA_PER_SQUARE_METER'      => array(1,'cd/m�'),
        'Lightness::FOOTLAMBERT'                   => array(3.4262591,'ftL'),
        'Lightness::KILOCANDELA_PER_SQUARE_CENTIMETER' => array(10000000,'kcd/cm�'),
        'Lightness::KILOCANDELA_PER_SQUARE_FOOT'   => array(10763.91,'kcd/ft�'),
        'Lightness::KILOCANDELA_PER_SQUARE_INCH'   => array(1550003.04,'kcd/in�'),
        'Lightness::KILOCANDELA_PER_SQUARE_METER'  => array(1000,'kcd/m�'),
        'Lightness::LAMBERT'                       => array(3183.0989,'L'),
        'Lightness::MILLILAMBERT'                  => array(3.1830989,'mL'),
        'Lightness::NIT'                           => array(1,'nt'),
        'Lightness::STILB'                         => array(10000,'sb')
    );

    private $_Locale;

    /**
     * Zend_Measure_Lightness provides an locale aware class for
     * conversion and formatting of Lightness values
     *
     * Zend_Measure $input can be a locale based input string
     * or a value. $locale can be used to define that the
     * input is made in a different language than the actual one.
     *
     * @param  $value  mixed  - Value as string, integer, real or float
     * @param  $type   type   - OPTIONAL a Zend_Measure_Lightness Type
     * @param  $locale locale - OPTIONAL a Zend_Locale Type
     * @throws Zend_Measure_Exception
     */
    public function __construct($value, $type, $locale = false)
    {
        if (empty($locale))
            $this->_Locale = new Zend_Locale();
        else
            $this->_Locale = $locale;

        $this->setValue($value, $type, $this->_Locale);
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
     * @param  $type   type   - OPTIONAL a Zend_Measure_Lightness Type
     * @param  $locale locale - OPTIONAL a Zend_Locale Type
     * @throws Zend_Measure_Exception
     */
    public function setValue($value, $type, $locale = false)
    {
        if (empty($locale))
            $locale = $this->_Locale;

        $value = Zend_Locale_Format::getNumber($value, $locale);
        if (empty(self::$_UNITS[$type]))
            self::throwException('unknown type of lightness:'.$type);
        parent::setValue($value, $type, $locale);
        parent::setType($type);
    }


    /**
     * Set a new type, and convert the value
     *
     * @throws Zend_Measure_Exception
     */
    public function setType($type)
    {
        if (empty(self::$_UNITS[$type]))
            self::throwException('unknown type of lightness:'.$type);

        // Convert to standard value
        $value = parent::getValue();
        $value = $value * (self::$_UNITS[parent::getType()][0]);

        // Convert to expected value
        $value = $value / (self::$_UNITS[$type][0]);
        parent::setValue($value, $type, $this->_Locale);
        parent::setType($type);
    }


    /**
     * Returns a string representation
     *
     * @return string
     */
    public function toString()
    {
        return parent::getValue().' '.self::$_UNITS[parent::getType()][1];
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