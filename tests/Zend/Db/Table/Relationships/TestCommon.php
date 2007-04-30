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
 * @see Zend_Db_Table_TestSetup
 */
require_once 'Zend/Db/Table/TestSetup.php';

/**
 * @see Zend_Loader
 */
require_once 'Zend/Loader.php';


PHPUnit_Util_Filter::addFileToFilter(__FILE__);


/**
 * @category   Zend
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Zend_Db_Table_Relationships_TestCommon extends Zend_Db_Table_TestSetup
{

    public function testTableRelationshipFindParentRow()
    {
        $table = $this->_table['bugs'];

        $childRows = $table->fetchAll('bug_id = 1');
        $this->assertThat($childRows, $this->isInstanceOf('Zend_Db_Table_Rowset_Abstract'),
            'Expecting object of type Zend_Db_Table_Rowset_Abstract');

        $childRow1 = $childRows->current();
        $this->assertThat($childRow1, $this->isInstanceOf('Zend_Db_Table_Row_Abstract'),
            'Expecting object of type Zend_Db_Table_Row_Abstract');

        $parentRow = $childRow1->findParentRow('Zend_Db_Table_TableAccounts');
        $this->assertThat($parentRow, $this->isInstanceOf('Zend_Db_Table_Row_Abstract'),
            'Expecting object of type Zend_Db_Table_Row_Abstract');

        $this->assertEquals('goofy', $parentRow->account_name);
    }

    public function testTableRelationshipMagicFindParentRow()
    {
        $table = $this->_table['bugs'];

        $childRows = $table->fetchAll('bug_id = 1');
        $this->assertThat($childRows, $this->isInstanceOf('Zend_Db_Table_Rowset_Abstract'),
            'Expecting object of type Zend_Db_Table_Rowset_Abstract');

        $childRow1 = $childRows->current();
        $this->assertThat($childRow1, $this->isInstanceOf('Zend_Db_Table_Row_Abstract'),
            'Expecting object of type Zend_Db_Table_Row_Abstract');

        $parentRow = $childRow1->findParentZend_Db_Table_TableAccounts();
        $this->assertThat($parentRow, $this->isInstanceOf('Zend_Db_Table_Row_Abstract'),
            'Expecting object of type Zend_Db_Table_Row_Abstract');

        $this->assertEquals('goofy', $parentRow->account_name);
    }

    public function testTableRelationshipMagicException()
    {
        $table = $this->_table['bugs'];

        $parentRows = $table->find(1);
        $parentRow1 = $parentRows->current();

        // Completely bogus method
        try {
            $result = $parentRow1->nonExistantMethod();
            $this->fail('Expected to catch Zend_Db_Table_Row_Exception');
        } catch (Exception $e) {
            $this->assertThat($e, $this->isInstanceOf('Zend_Db_Table_Row_Exception'),
                'Expecting object of type Zend_Db_Table_Row_Exception got '.get_class($e));
            $this->assertEquals("Unrecognized method 'nonExistantMethod()'", $e->getMessage());
        }
    }

    public function testTableRelationshipFindParentRowException()
    {
        $table = $this->_table['bugs'];

        $childRows = $table->fetchAll('bug_id = 1');
        $childRow1 = $childRows->current();

        try {
            $parentRow = $childRow1->findParentRow('nonexistant_class');
            $this->fail('Expected to catch Zend_Db_Table_Row_Exception for nonexistent table class');
        } catch (Exception $e) {
            $this->assertThat($e, $this->isInstanceOf('Zend_Db_Table_Row_Exception'),
                'Expecting object of type Zend_Db_Table_Row_Exception got '.get_class($e));
            $this->assertEquals('File "class.php" was not found', $e->getMessage());
        }

        try {
            $parentRow = $childRow1->findParentRow(new stdClass());
            $this->fail('Expected to catch Zend_Db_Table_Row_Exception for wrong table class');
        } catch (Exception $e) {
            $this->assertThat($e, $this->isInstanceOf('Zend_Db_Table_Exception'),
                'Expecting object of type Zend_Db_Table_Exception got '.get_class($e));
            $this->assertEquals('Parent table must be a Zend_Db_Table_Abstract, but it is stdClass', $e->getMessage());
        }
    }

    public function testTableRelationshipFindManyToManyRowset()
    {
        $table = $this->_table['bugs'];

        $originRows = $table->find(1);
        $originRow1 = $originRows->current();

        $destRows = $originRow1->findManyToManyRowset('Zend_Db_Table_TableProducts', 'Zend_Db_Table_TableBugsProducts');
        $this->assertThat($destRows, $this->isInstanceOf('Zend_Db_Table_Rowset_Abstract'),
            'Expecting object of type Zend_Db_Table_Rowset_Abstract');

        $this->assertEquals(3, $destRows->count());
    }

    public function testTableRelationshipMagicFindManyToManyRowset()
    {
        $table = $this->_table['bugs'];

        $originRows = $table->find(1);
        $originRow1 = $originRows->current();

        $destRows = $originRow1->findZend_Db_Table_TableProductsViaZend_Db_Table_TableBugsProducts();
        $this->assertThat($destRows, $this->isInstanceOf('Zend_Db_Table_Rowset_Abstract'),
            'Expecting object of type Zend_Db_Table_Rowset_Abstract');

        $this->assertEquals(3, $destRows->count());
    }

    public function testTableRelationshipFindManyToManyRowsetException()
    {
        $table = $this->_table['bugs'];

        $originRows = $table->find(1);
        $originRow1 = $originRows->current();

        // Use nonexistant class for destination table
        try {
            $destRows = $originRow1->findManyToManyRowset('nonexistant_class', 'Zend_Db_Table_TableBugsProducts');
            $this->fail('Expected to catch Zend_Db_Table_Row_Exception for nonexistent table class');
        } catch (Exception $e) {
            $this->assertThat($e, $this->isInstanceOf('Zend_Db_Table_Exception'),
                'Expecting object of type Zend_Db_Table_Exception got '.get_class($e));
            $this->assertEquals('File "class.php" was not found', $e->getMessage());
        }

        // Use stdClass instead of table class for destination table
        try {
            $destRows = $originRow1->findManyToManyRowset(new stdClass(), 'Zend_Db_Table_TableBugsProducts');
            $this->fail('Expected to catch Zend_Db_Table_Row_Exception for nonexistent table class');
        } catch (Exception $e) {
            $this->assertThat($e, $this->isInstanceOf('Zend_Db_Table_Exception'),
                'Expecting object of type Zend_Db_Table_Exception got '.get_class($e));
            $this->assertEquals('Match table must be a Zend_Db_Table_Abstract, but it is stdClass', $e->getMessage());
        }

        // Use nonexistant class for intersection table
        try {
            $destRows = $originRow1->findManyToManyRowset('Zend_Db_Table_TableProducts', 'nonexistant_class');
            $this->fail('Expected to catch Zend_Db_Table_Row_Exception for nonexistent table class');
        } catch (Exception $e) {
            $this->assertThat($e, $this->isInstanceOf('Zend_Db_Table_Exception'),
                'Expecting object of type Zend_Db_Table_Exception got '.get_class($e));
            $this->assertEquals('File "class.php" was not found', $e->getMessage());
        }

        // Use stdClass instead of table class for intersection table
        try {
            $destRows = $originRow1->findManyToManyRowset('Zend_Db_Table_TableProducts', new stdClass());
            $this->fail('Expected to catch Zend_Db_Table_Row_Exception for nonexistent table class');
        } catch (Exception $e) {
            $this->assertThat($e, $this->isInstanceOf('Zend_Db_Table_Exception'),
                'Expecting object of type Zend_Db_Table_Exception got '.get_class($e));
            $this->assertEquals('Intersection table must be a Zend_Db_Table_Abstract, but it is stdClass', $e->getMessage());
        }

    }

    public function testTableRelationshipFindDependentRowset()
    {
        $table = $this->_table['bugs'];

        $parentRows = $table->find(1);
        $this->assertThat($parentRows, $this->isInstanceOf('Zend_Db_Table_Rowset_Abstract'),
            'Expecting object of type Zend_Db_Table_Rowset_Abstract');
        $parentRow1 = $parentRows->current();

        $childRows = $parentRow1->findDependentRowset('Zend_Db_Table_TableBugsProducts');
        $this->assertThat($childRows, $this->isInstanceOf('Zend_Db_Table_Rowset_Abstract'),
            'Expecting object of type Zend_Db_Table_Rowset_Abstract');

        $this->assertEquals(3, $childRows->count());

        $childRow1 = $childRows->current();
        $this->assertThat($childRow1, $this->isInstanceOf('Zend_Db_Table_Row_Abstract'),
            'Expecting object of type Zend_Db_Table_Row_Abstract');

        $this->assertEquals(1, $childRow1->bug_id);
        $this->assertEquals(1, $childRow1->product_id);
    }

    public function testTableRelationshipMagicFindDependentRowset()
    {
        $table = $this->_table['bugs'];

        $parentRows = $table->find(1);
        $parentRow1 = $parentRows->current();

        $childRows = $parentRow1->findZend_Db_Table_TableBugsProducts();
        $this->assertThat($childRows, $this->isInstanceOf('Zend_Db_Table_Rowset_Abstract'),
            'Expecting object of type Zend_Db_Table_Rowset_Abstract');

        $this->assertEquals(3, $childRows->count());

        $childRow1 = $childRows->current();
        $this->assertThat($childRow1, $this->isInstanceOf('Zend_Db_Table_Row_Abstract'),
            'Expecting object of type Zend_Db_Table_Row_Abstract');

        $this->assertEquals(1, $childRow1->bug_id);
        $this->assertEquals(1, $childRow1->product_id);
    }

    public function testTableRelationshipFindDependentRowsetException()
    {
        $table = $this->_table['bugs'];

        $parentRows = $table->find(1);
        $parentRow1 = $parentRows->current();

        try {
            $childRows = $parentRow1->findDependentRowset('nonexistant_class');
            $this->fail('Expected to catch Zend_Db_Table_Row_Exception for nonexistent table class');
        } catch (Exception $e) {
            $this->assertThat($e, $this->isInstanceOf('Zend_Db_Table_Exception'),
                'Expecting object of type Zend_Db_Table_Exception got '.get_class($e));
            $this->assertEquals('File "class.php" was not found', $e->getMessage());
        }

        try {
            $childRows = $parentRow1->findDependentRowset(new stdClass());
            $this->fail('Expected to catch Zend_Db_Table_Row_Exception for wrong table class');
        } catch (Exception $e) {
            $this->assertThat($e, $this->isInstanceOf('Zend_Db_Table_Row_Exception'),
                'Expecting object of type Zend_Db_Table_Row_Exception got '.get_class($e));
            $this->assertEquals('Dependent table must be a Zend_Db_Table_Abstract, but it is stdClass', $e->getMessage());
        }
    }

    /*
    public function testTableRelationshipCascadingUpdate()
    {
        $table = $this->_table['bugs'];

        $parentRows = $table->find(1);
        $this->assertThat($parentRows, $this->isInstanceOf('Zend_Db_Table_Rowset_Abstract'),
            'Expecting object of type Zend_Db_Table_Rowset_Abstract');
        $parentRow1 = $parentRows->current();

        $childRows = $parentRow1->findDependentRowset('Zend_Db_Table_TableBugsProducts');
        $this->assertEquals(3, $childRows->count());

        $total = 0;
        foreach ($childRows as $row) {
            $this->assertEquals(1, $row->bug_id);
        }

        $parentRow1->setFromArray(array('bug_id' => 101));
        $parentRow1->save();

        $childRows = $parentRow1->findDependentRowset('Zend_Db_Table_TableBugsProducts');
        $this->assertEquals(3, $childRows->count());

        $total = 0;
        foreach ($childRows as $row) {
            $this->assertEquals(1, $row->bug_id);
        }

        $parentRow1->setFromArray(array('bug_id' => 1));
        $parentRow1->save();

        $childRows = $parentRow1->findDependentRowset('Zend_Db_Table_TableBugsProducts');
        $this->assertEquals(3, $childRows->count());

        $total = 0;
        foreach ($childRows as $row) {
            $this->assertEquals(1, $row->bug_id);
        }
    }
     */

    /**
     * Ensures that basic cascading delete functionality succeeds using strings for single columns
     *
     * @return void
     */
    public function testTableRelationshipCascadingDeleteUsageBasicString()
    {
        $bug1 = $this->_getTable('Zend_Db_Table_TableBugsCustom')
                ->find(1)
                ->current();

        $this->assertEquals(
            3,
            count($bug1->findDependentRowset('Zend_Db_Table_TableBugsProductsCustom')),
            'Expecting to find three dependent rows'
            );

        $bug1->delete();

        $this->assertEquals(
            0,
            count($this->_getTable('Zend_Db_Table_TableBugsProductsCustom')->fetchAll('bug_id = 1')),
            'Expecting cascading delete to have reduced dependent rows to zero'
            );
    }

    /**
     * Ensures that basic cascading delete functionality succeeds using arrays for single columns
     *
     * @return void
     */
    public function testTableRelationshipCascadingDeleteUsageBasicArray()
    {
        $account1 = $this->_getTable('Zend_Db_Table_TableAccountsCustom')
                    ->find('mmouse')
                    ->current();

        $this->assertEquals(
            1,
            count($account1->findDependentRowset('Zend_Db_Table_TableBugsCustom')),
            'Expecting to find one dependent row'
            );

        $account1->delete();

        $tableBugsCustom = $this->_getTable('Zend_Db_Table_TableBugsCustom');

        $this->assertEquals(
            0,
            count(
                $tableBugsCustom->fetchAll(
                    $tableBugsCustom->getAdapter()
                                    ->quoteInto('reported_by = ?', 'mmouse')
                    )
                ),
            'Expecting cascading delete to have reduced dependent rows to zero'
            );
    }

    /**
     * Ensures that cascading delete functionality is not run when onDelete != self::CASCADE
     *
     * @return void
     */
    public function testTableRelationshipCascadingDeleteUsageInvalidNoop()
    {
        $product1 = $this->_getTable('Zend_Db_Table_TableProductsCustom')
                    ->find(1)
                    ->current();

        $this->assertEquals(
            1,
            count($product1->findDependentRowset('Zend_Db_Table_TableBugsProductsCustom')),
            'Expecting to find one dependent row'
            );

        $product1->delete();

        $this->assertEquals(
            1,
            count($this->_getTable('Zend_Db_Table_TableBugsProductsCustom')->fetchAll('product_id = 1')),
            'Expecting to find one dependent row'
            );
    }

    public function testTableRelationshipGetReference()
    {
        $table = $this->_table['bugs'];

        $map = $table->getReference('Zend_Db_Table_TableAccounts', 'Reporter');

        $this->assertThat($map, $this->arrayHasKey('columns'));
        $this->assertThat($map, $this->arrayHasKey('refTableClass'));
        $this->assertThat($map, $this->arrayHasKey('refColumns'));
    }

    public function testTableRelationshipGetReferenceException()
    {
        $table = $this->_table['bugs'];

        try {
            $table->getReference('Zend_Db_Table_TableAccounts', 'Nonexistent');
            $this->fail('Expected to catch Zend_Db_Table_Exception for nonexistent reference rule');
        } catch (Exception $e) {
            $this->assertThat($e, $this->isInstanceOf('Zend_Db_Table_Exception'),
                'Expecting object of type Zend_Db_Table_Exception got '.get_class($e));
        }

        try {
            $table->getReference('Nonexistent', 'Reporter');
            $this->fail('Expected to catch Zend_Db_Table_Exception for nonexistent rule tableClass');
        } catch (Exception $e) {
            $this->assertThat($e, $this->isInstanceOf('Zend_Db_Table_Exception'),
                'Expecting object of type Zend_Db_Table_Exception got '.get_class($e));
        }

        try {
            $table->getReference('Nonexistent');
            $this->fail('Expected to catch Zend_Db_Table_Exception for nonexistent rule tableClass');
        } catch (Exception $e) {
            $this->assertThat($e, $this->isInstanceOf('Zend_Db_Table_Exception'),
                'Expecting object of type Zend_Db_Table_Exception got '.get_class($e));
        }
    }

    /**
     * Ensures that findParentRow() returns an instance of a custom row class when passed an instance
     * of the table class having $_rowClass overridden.
     *
     * @return void
     */
    public function testTableRelationshipFindParentRowCustomInstance()
    {
        $myRowClass = 'Zend_Db_Table_Row_TestMyRow';

        Zend_Loader::loadClass($myRowClass);

        $bug1Reporter = $this->_table['bugs']
                        ->find(1)
                        ->current()
                        ->findParentRow($this->_table['accounts']->setRowClass($myRowClass));

        $this->assertThat($bug1Reporter, $this->isInstanceOf($myRowClass),
            "Expecting object of type $myRowClass");
    }

    /**
     * Ensures that findParentRow() returns an instance of a custom row class when passed a string class
     * name, where the class has $_rowClass overridden.
     *
     * @return void
     */
    public function testTableRelationshipFindParentRowCustomClass()
    {
        $myRowClass = 'Zend_Db_Table_Row_TestMyRow';

        Zend_Loader::loadClass($myRowClass);

        Zend_Loader::loadClass('Zend_Db_Table_TableAccountsCustom');

        $bug1Reporter = $this->_getTable('Zend_Db_Table_TableBugsCustom')
                        ->find(1)
                        ->current()
                        ->findParentRow(new Zend_Db_Table_TableAccountsCustom(array('db' => $this->_db)));

        $this->assertThat($bug1Reporter, $this->isInstanceOf($myRowClass),
            "Expecting object of type $myRowClass");
    }

    /**
     * Ensures that findDependentRowset() returns instances of custom row and rowset classes when
     * passed an instance of the table class.
     *
     * @return void
     */
    public function testTableRelationshipFindDependentRowsetCustomInstance()
    {
        $myRowsetClass = 'Zend_Db_Table_Rowset_TestMyRowset';
        $myRowClass    = 'Zend_Db_Table_Row_TestMyRow';

        Zend_Loader::loadClass($myRowsetClass);

        $bugs = $this->_table['accounts']
                ->fetchRow($this->_db->quoteInto('account_name = ?', 'mmouse'))
                ->findDependentRowset(
                    $this->_table['bugs']
                        ->setRowsetClass($myRowsetClass)
                        ->setRowClass($myRowClass),
                    'Engineer'
                    );

        $this->assertThat($bugs, $this->isInstanceOf($myRowsetClass),
            "Expecting object of type $myRowsetClass");

        $this->assertEquals(3, count($bugs));

        foreach ($bugs as $bug) {
            $this->assertThat($bug, $this->isInstanceOf($myRowClass));
        }
    }

    /**
     * Ensures that findDependentRowset() returns instances of custom row and rowset classes when
     * passed the named class.
     *
     * @return void
     */
    public function testTableRelationshipFindDependentRowsetCustomClass()
    {
        $myRowsetClass = 'Zend_Db_Table_Rowset_TestMyRowset';
        $myRowClass    = 'Zend_Db_Table_Row_TestMyRow';

        Zend_Loader::loadClass($myRowsetClass);

        $bugs = $this->_getTable('Zend_Db_Table_TableAccountsCustom')
                ->fetchRow($this->_db->quoteInto('account_name = ?', 'mmouse'))
                ->findDependentRowset('Zend_Db_Table_TableBugsCustom', 'Engineer');

        $this->assertThat($bugs, $this->isInstanceOf($myRowsetClass),
            "Expecting object of type $myRowsetClass");

        $this->assertEquals(3, count($bugs));

        foreach ($bugs as $bug) {
            $this->assertThat($bug, $this->isInstanceOf($myRowClass));
        }
    }

    /**
     * Ensures that findManyToManyRowset() returns instances of custom row and rowset class when
     * passed an instance of the table class.
     *
     * @return void
     */
    public function testTableRelationshipFindManyToManyRowsetCustomInstance()
    {
        $myRowsetClass = 'Zend_Db_Table_Rowset_TestMyRowset';
        $myRowClass    = 'Zend_Db_Table_Row_TestMyRow';

        Zend_Loader::loadClass($myRowsetClass);

        $bug1Products = $this->_table['bugs']
                        ->find(1)
                        ->current()
                        ->findManyToManyRowset(
                            $this->_table['products']
                                ->setRowsetClass($myRowsetClass)
                                ->setRowClass($myRowClass),
                            'Zend_Db_Table_TableBugsProducts'
                            );

        $this->assertThat($bug1Products, $this->isInstanceOf($myRowsetClass),
            "Expecting object of type $myRowsetClass");

        $this->assertEquals(3, count($bug1Products));

        foreach ($bug1Products as $bug1Product) {
            $this->assertThat($bug1Product, $this->isInstanceOf($myRowClass));
        }
    }

    /**
     * Ensures that findManyToManyRowset() returns instances of custom row and rowset classes when
     * passed the named class.
     *
     * @return void
     */
    public function testTableRelationshipFindManyToManyRowsetCustomClass()
    {
        $myRowsetClass = 'Zend_Db_Table_Rowset_TestMyRowset';
        $myRowClass    = 'Zend_Db_Table_Row_TestMyRow';

        Zend_Loader::loadClass($myRowsetClass);

        $bug1Products = $this->_getTable('Zend_Db_Table_TableBugsCustom')
                        ->find(1)
                        ->current()
                        ->findManyToManyRowset(
                            'Zend_Db_Table_TableProductsCustom',
                            'Zend_Db_Table_TableBugsProductsCustom'
                            );

        $this->assertThat($bug1Products, $this->isInstanceOf($myRowsetClass),
            "Expecting object of type $myRowsetClass");

        $this->assertEquals(3, count($bug1Products));

        foreach ($bug1Products as $bug1Product) {
            $this->assertThat($bug1Product, $this->isInstanceOf($myRowClass));
        }
    }

}
