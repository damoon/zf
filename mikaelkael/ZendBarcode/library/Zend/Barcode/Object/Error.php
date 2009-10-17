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
 * @package    Zend_Barcode
 * @subpackage Barcode
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
require_once 'Zend/Barcode/Object.php';

/**
 * Class for generate Barcode
 *
 * @category   Zend
 * @package    Zend_Barcode
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Barcode_Object_Error extends Zend_Barcode_Object
{

    /**
     * All texts are accepted
     * @param string $value
     * @return boolean
     */
    public function validateText($value)
    {
        return true;
    }

    /**
     * Height is forced
     * @return integer
     */
    public function getHeight()
    {
        return 40;
    }

    /**
     * Width is forced
     * @return integer
     */
    public function getWidth()
    {
        return 300;
    }

    /**
     * Reset precedent instructions
     * and draw the error message
     * @return array
     */
    public function draw()
    {
        $this->_instructions = array();
        $this->addText('ERROR:', 10, array(5 , 18), $this->_font, 0);
        $this->addText($this->_text, 10, array(5 , 32), $this->_font, 0);
        return $this->_instructions;
    }

    /**
     * For compatibility reason
     * @return void
     */
    protected function _prepareBarcode()
    {}

    /**
     * For compatibility reason
     * @return void
     */
    protected function _checkParams()
    {}

    /**
     * For compatibility reason
     * @return void
     */
    protected function _calculateBarcodeWidth()
    {}

    /**
     * For compatibility reason
     * @return void
     */
    protected function _characterLength()
    {}
}