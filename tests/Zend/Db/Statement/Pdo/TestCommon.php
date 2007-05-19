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
 */

require_once 'Zend/Db/Statement/TestCommon.php';

PHPUnit_Util_Filter::addFileToFilter(__FILE__);

abstract class Zend_Db_Statement_Pdo_TestCommon extends Zend_Db_Statement_TestCommon
{

    public function testStatementConstruct()
    {
        $select = $this->_db->select()
            ->from('zfproducts');
        $sql = $select->__toString();
        $stmt = new PDOStatement($this->_db->getConnection(), $sql);
        $this->assertType('PDOStatement', $stmt);
    }

    public function testStatementConstructFromPrepare()
    {
        $select = $this->_db->select()
            ->from('zfproducts');
        $stmt = $this->_db->prepare($select->__toString());
        $this->assertType('PDOStatement', $stmt);
        $stmt->closeCursor();
    }

    public function testStatementConstructFromQuery()
    {
        $select = $this->_db->select()
            ->from('zfproducts');
        $stmt = $this->_db->query($select);
        $this->assertType('PDOStatement', $stmt);
        $stmt->closeCursor();
    }

    public function testStatementConstructFromSelect()
    {
        $stmt = $this->_db->select()
            ->from('zfproducts')
            ->query();
        $this->assertType('PDOStatement', $stmt);
        $stmt->closeCursor();
    }

    public function testStatementBindParamException()
    {
        $this->markTestSkipped('PDO does not throw an exception from bindParam() by default.');
    }

}
