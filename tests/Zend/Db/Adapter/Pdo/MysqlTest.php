<?php
/**
 * @package    Zend_Db
 * @subpackage UnitTests
 */

/**
 * Common class is DB independant
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Common.php';


/**
 * @package    Zend_Db_Adapter_Pdo_MysqlTest
 * @subpackage UnitTests
 */
class Zend_Db_Adapter_Pdo_MysqlTest extends Zend_Db_Adapter_Pdo_Common
{

    function getCreateTableSQL()
    {
        return 'CREATE TABLE  '. self::TableName . '
            (id INT NOT NULL auto_increment, title VARCHAR(100), subTitle VARCHAR (100),
            body TEXT, date_created DATETIME, PRIMARY KEY (id))';
    }

    function getDriver()
    {
        return 'pdo_Mysql';
    }

    function getParams()
    {
        $params = array (
            'host'     => TESTS_ZEND_DB_ADAPTER_PDO_MYSQL_HOSTNAME,
            'username' => TESTS_ZEND_DB_ADAPTER_PDO_MYSQL_USERNAME,
            'password' => TESTS_ZEND_DB_ADAPTER_PDO_MYSQL_PASSWORD,
            'dbname'   => TESTS_ZEND_DB_ADAPTER_PDO_MYSQL_DATABASE
        );

        return $params;
    }


    public function testQuote()
    {
        // test double quotes are fine
        $value = $this->_db->quote('St John"s Wort');
        $this->assertEquals("'St John\\\"s Wort'", $value);

        // test that single quotes are escaped with another single quote
        $value = $this->_db->quote("St John's Wort");
        $this->assertEquals("'St John\\'s Wort'", $value);

        // quote an array
        $value = $this->_db->quote(array("it's", 'all', 'right!'));
        $this->assertEquals("'it\\'s', 'all', 'right!'", $value);

        // test numeric
        $value = $this->_db->quote('1');
        $this->assertEquals("'1'", $value);

        $value = $this->_db->quote(1);
        $this->assertEquals("'1'", $value);

        $value = $this->_db->quote(array(1,'2',3));
        $this->assertEquals("'1', '2', '3'", $value);
    }

    public function testQuoteInto()
    {
        // test double quotes are fine
        $value = $this->_db->quoteInto('id=?', 'St John"s Wort');
        $this->assertEquals("id='St John\\\"s Wort'", $value);

        // test that single quotes are escaped with another single quote
        $value = $this->_db->quoteInto('id = ?', 'St John\'s Wort');
        $this->assertEquals("id = 'St John\\'s Wort'", $value);
    }

    public function testQuoteIdentifier()
    {
        $value = $this->_db->quoteIdentifier('table_name');
        $this->assertEquals("`table_name`", $value);
        $value = $this->_db->quoteIdentifier('table_`_name');
        $this->assertEquals("`table_``_name`", $value);
    }

    function testDescribeTable()
    {
        $descr = $this->_db->describeTable(self::TableName);
        $this->assertEquals($descr['id']['name'], 'id');
        $this->assertEquals($descr['id']['type'], 'int(11)');
        $this->assertEquals($descr['id']['primary'], 1);
    }

}
