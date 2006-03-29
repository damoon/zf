<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to version 1.0 of the Zend Framework
 * license, that is bundled with this package in the file LICENSE, and
 * is available through the world-wide-web at the following URL:
 * http://www.zend.com/license/framework/1_0.txt. If you did not receive
 * a copy of the Zend Framework license and are unable to obtain it
 * through the world-wide-web, please send a note to license@zend.com
 * so we can mail you a copy immediately.
 *
 * @package    Zend_Search_Lucene
 * @subpackage Analysis
 * @copyright  Copyright (c) 2005-2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://www.zend.com/license/framework/1_0.txt Zend Framework License version 1.0
 */


/** Zend_Search_Lucene_Analysis_Analyzer */
require_once 'Zend/Search/Lucene/Analysis/Analyzer.php';


/**
 * Common implementation of the Zend_Search_Lucene_Analysis_Analyzer interface.
 * There are several standard standard subclasses provided by Zend_Search_Lucene/Analysis
 * subpackage: ZSearchTextAnalyzer, ZSearchHTMLAnalyzer, ZSearchXMLAnalyzer.
 *
 * @todo ZSearchHTMLAnalyzer and ZSearchXMLAnalyzer implementation
 *
 * @package    Zend_Search_Lucene
 * @subpackage Analysis
 * @copyright  Copyright (c) 2005-2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://www.zend.com/license/framework/1_0.txt Zend Framework License version 1.0
 */
abstract class ZSearchCommonAnalyzer extends Zend_Search_Lucene_Analysis_Analyzer
{
    /**
     * The set of Token filters applied to the Token stream.
     * Array of ZSearchTokenFilter objects.
     *
     * @var array
     */
    private $_filters = array();

    /**
     * Add Token filter to the Analyzer
     *
     * @param ZSearchTokenFilter $filter
     */
    public function addFilter(ZSearchTokenFilter $filter)
    {
        $this->_filters[] = $filter;
    }

    /**
     * Apply filters to the token.
     *
     * @param ZSearchToken $token
     * @return ZSearchToken
     */
    public function normalize(ZSearchToken $token)
    {
        foreach ($this->_filters as $filter) {
            $token = $filter->normalize($token);
        }

        return $token;
    }
}

