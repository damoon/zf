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
 * @package    Zend_Translate
 * @copyright  Copyright (c) 2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @version    $Id: Date.php 2498 2006-12-23 22:13:38Z thomas $
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


/** Zend_Locale */
require_once 'Zend/Locale.php';

/** Zend_Translate_Exception */
require_once 'Zend/Translate/Exception.php';

/** Zend_Translate_Adapter */
require_once 'Zend/Translate/Adapter.php';


/**
 * @category   Zend
 * @package    Zend_Translate
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Translate_Adapter_Array extends Zend_Translate_Adapter {
    /**
     * Generates the adapter
     *
     * @param array $options - Options for this adapter
     * @param mixed $locale  - OPTIONAL locale to use
     */
    public function __construct($options, $locale = null)
    {
        parent::__construct($options, $locale);
    }

    /**
     * Load translation data
     *
     * @param string $language
     * @param mixed $data
     */
    protected function _loadTranslationData($language, $data)
    {
        $this->_translate[$language] = array_merge($this->_translate[$language], $data);
    }

    /**
     * returns the adapters name
     *
     * @return string
     */
    public function toString()
    {
        return "Array";
    }
}
