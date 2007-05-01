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
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * @see Zend_Db_TestUtil_Pdo_Common
 */
require_once 'Zend/Db/TestUtil/Pdo/Common.php';


PHPUnit_Util_Filter::addFileToFilter(__FILE__);


/**
 * @category   Zend
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Db_TestUtil_Pdo_Pgsql extends Zend_Db_TestUtil_Pdo_Common
{

    public function getParams(array $constants = array())
    {
        $constants = array (
            'host'     => 'TESTS_ZEND_DB_ADAPTER_PDO_PGSQL_HOSTNAME',
            'username' => 'TESTS_ZEND_DB_ADAPTER_PDO_PGSQL_USERNAME',
            'password' => 'TESTS_ZEND_DB_ADAPTER_PDO_PGSQL_PASSWORD',
            'dbname'   => 'TESTS_ZEND_DB_ADAPTER_PDO_PGSQL_DATABASE'
        );
        return parent::getParams($constants);
    }

    public function getSchema()
    {
        return 'public';
    }

    /**
     * For PostgreSQL, override the Products table to use an
     * explicit sequence-based column.
     */
    protected function _getColumnsProducts()
    {
        return array(
            'product_id'   => 'INT NOT NULL PRIMARY KEY',
            'product_name' => 'VARCHAR(100)'
        );
    }

    protected function _getDataProducts(Zend_Db_Adapter_Abstract $db)
    {
        $data = parent::_getDataProducts($db);
        foreach ($data as &$row) {
            $row['product_id'] = new Zend_Db_Expr('NEXTVAL('.$db->quote('products_seq').')');
        }
        return $data;
    }

    public function getSqlType($type)
    {
        if ($type == 'IDENTITY') {
            return 'SERIAL PRIMARY KEY';
        }
        if ($type == 'DATETIME') {
            return 'TIMESTAMP';
        }
        return $type;
    }

    public function setUp(Zend_Db_Adapter_Abstract $db)
    {
        $this->createSequence($db, 'products_seq');
        parent::setUp($db);
    }

    protected function _getSqlDropTable(Zend_Db_Adapter_Abstract $db, $tableName)
    {
        return 'DROP TABLE IF EXISTS ' . $db->quoteIdentifier($tableName);
    }

    protected function _getSqlCreateSequence(Zend_Db_Adapter_Abstract $db, $sequenceName)
    {
        return 'CREATE SEQUENCE ' . $db->quoteIdentifier($sequenceName);
    }

    protected function _getSqlDropSequence(Zend_Db_Adapter_Abstract $db, $sequenceName)
    {
        return 'DROP SEQUENCE IF EXISTS ' . $db->quoteIdentifier($sequenceName);
    }

}
