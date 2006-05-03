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
 * @package    Zend_Db
 * @subpackage Adapter
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */ 


/**
 * Zend_Db_Adapter_Pdo
 */
require_once 'Zend/Db/Adapter/Pdo/Abstract.php';


/**
 * Class for connecting to MySQL databases and performing common operations.
 *
 * @package    Zend_Db
 * @subpackage Adapter
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Db_Adapter_Pdo_Mysql extends Zend_Db_Adapter_Pdo_Abstract
{

    /**
     * PDO type.
     *
     * @var string
     */
    protected $_pdoType = 'mysql';


    /**
     * Quotes an identifier.
     *
     * @param string $ident The identifier.
     * @return string The quoted identifier.
     */
    public function quoteIdentifier($ident)
    {
        $ident = str_replace('`', '\`', $ident);
        return "`$ident`";
    }


    /**
     * Returns a list of the tables in the database.
     *
     * @return array
     */
    public function listTables()
    {
        return $this->fetchCol('SHOW TABLES');
    }


    /**
     * Returns the column descriptions for a table.
     *
     * @return array
     */
    public function describeTable($table)
    {
        $sql = "DESCRIBE $table";
        $result = $this->fetchAll($sql);
        $descr = array();
        foreach ($result as $key => $val) {
            $descr[$val['field']] = array(
                'name'    => $val['field'],
                'type'    => $val['type'],
                'notnull' => (bool) ($val['null'] === ''), // not null is empty, null is yes
                'default' => $val['default'],
                'primary' => (strtolower($val['key']) == 'pri'),
            );
        }
        return $descr;
    }


    /**
     * Adds an adapter-specific LIMIT clause to the SELECT statement.
     *
     * @return string
     */
     public function limit($sql, $count, $offset)
     {
        if ($count > 0) {
            $offset = ($offset > 0) ? $offset : 0;
            $sql .= "LIMIT $offset, $count";
        }
        return $sql;
    }
}
