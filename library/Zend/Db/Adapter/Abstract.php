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
 * @subpackage Adapter
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * @see Zend_Db
 */
require_once 'Zend/Db.php';

/**
 * @see Zend_Db_Profiler
 */
require_once 'Zend/Db/Profiler.php';

/**
 * @see Zend_Db_Select
 */
require_once 'Zend/Db/Select.php';


/**
 * Class for connecting to SQL databases and performing common operations.
 *
 * @category   Zend
 * @package    Zend_Db
 * @subpackage Adapter
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Zend_Db_Adapter_Abstract
{

    /**
     * User-provided configuration
     *
     * @var array
     */
    protected $_config = array();

    /**
     * Fetch mode
     *
     * @var integer
     */
    protected $_fetchMode = Zend_Db::FETCH_ASSOC;

    /**
     * Query profiler
     *
     * @var Zend_Db_Profiler
     */
    protected $_profiler;

    /**
     * Database connection
     *
     * @var object|resource|null
     */
    protected $_connection = null;

    /**
     * Specifies the case of column names retrieved in queries
     * Options
     * Zend_Db::CASE_NATURAL (default)
     * Zend_Db::CASE_LOWER
     * Zend_Db::CASE_UPPER
     *
     * @access protected
     */
    protected $_caseFolding = Zend_Db::CASE_NATURAL;

    /**
     * Specifies whether the adapter automatically quotes identifiers.
     * If true, most SQL generated by Zend_Db classes applies
     * identifier quoting automatically.
     * If false, developer must quote identifiers themselves
     * by calling quoteIdentifier().
     *
     * @access protected
     */
    protected $_autoQuoteIdentifiers = true;

    /**
     * Keys are UPPERCASE SQL datatypes or the constants
     * Zend_Db::INT_TYPE, Zend_Db::BIGINT_TYPE, or Zend_Db::FLOAT_TYPE.
     *
     * Values are:
     * 0 = 32-bit integer
     * 1 = 64-bit integer
     * 2 = float or decimal
     *
     * @var array $_numericDataTypes
     */
    protected $_numericDataTypes = array(
        Zend_Db::INT_TYPE    => Zend_Db::INT_TYPE,
        Zend_Db::BIGINT_TYPE => Zend_Db::BIGINT_TYPE,
        Zend_Db::FLOAT_TYPE  => Zend_Db::FLOAT_TYPE
    );

    /**
     * Constructor.
     *
     * $config is an array of key/value pairs containing configuration
     * options.  These options are common to most adapters:
     *
     * dbname         => (string) The name of the database to user
     * username       => (string) Connect to the database as this username.
     * password       => (string) Password associated with the username.
     * host           => (string) What host to connect to, defaults to localhost
     *
     * Some options are used on a case-by-case basis by adapters:
     *
     * port           => (string) The port of the database
     * persistent     => (boolean) Whether to use a persistent connection or not, defaults to false
     * protocol       => (string) The network protocol, defaults to TCPIP
     * caseFolding    => (int)
     *
     * @param array $config An array of configuration keys.
     * @throws Zend_Db_Adapter_Exception
     */
    public function __construct(array $config = array())
    {
        $this->_checkRequiredOptions($config);

        $options = array(
            Zend_Db::CASE_FOLDING           => $this->_caseFolding,
            Zend_DB::AUTO_QUOTE_IDENTIFIERS => $this->_autoQuoteIdentifiers
        );
        $driver_options = array();

        // normalize the config and merge it with the defaults
        if (array_key_exists('options', $config)) {
            // can't use array_merge() because keys might be integers
            foreach ((array) $config['options'] as $key => $value) {
                $options[$key] = $value;
            }
        }
        if (array_key_exists('driver_options', $config)) {
            // can't use array_merge() because keys might be integers
            foreach ((array) $config['driver_options'] as $key => $value) {
                $driver_options[$key] = $value;
            }
        }
        $this->_config  = array_merge($this->_config, $config);
        $this->_config['options'] = $options;
        $this->_config['driver_options'] = $driver_options;

        // obtain the case setting, if there is one
        if (array_key_exists(Zend_Db::CASE_FOLDING, $options)) {
            $case = (int) $options[Zend_Db::CASE_FOLDING];
            switch ($case) {
                case Zend_Db::CASE_LOWER:
                case Zend_Db::CASE_UPPER:
                case Zend_Db::CASE_NATURAL:
                    $this->_caseFolding = $case;
                    break;
                default:
                    require_once 'Zend/Db/Adapter/Exception.php';
                    throw new Zend_Db_Adapter_Exception("Case must be one of the following constants: Zend_Db::CASE_NATURAL, Zend_Db::CASE_LOWER, Zend_Db::CASE_UPPER");
            }
        }

        // obtain quoting property if there is one
        if (array_key_exists(Zend_Db::AUTO_QUOTE_IDENTIFIERS, $options)) {
            $this->_autoQuoteIdentifiers = (bool) $options[Zend_Db::AUTO_QUOTE_IDENTIFIERS];
        }

        // create a profiler object
        $enabled = false;
        if (array_key_exists('profiler', $this->_config)) {
            $enabled = (bool) $this->_config['profiler'];
            unset($this->_config['profiler']);
        }

        $this->_profiler = new Zend_Db_Profiler($enabled);
    }

    /**
     * Check for config options that are mandatory.
     * Throw exceptions if any are missing.
     *
     * @param array $config
     * @throws Zend_Db_Adapter_Exception
     */
    protected function _checkRequiredOptions(array $config)
    {
        // we need at least a dbname
        if (! array_key_exists('dbname', $config)) {
            require_once 'Zend/Db/Adapter/Exception.php';
            throw new Zend_Db_Adapter_Exception("Configuration array must have a key for 'dbname' that names the database instance.");
        }

        if (! array_key_exists('password', $config)) {
            /**
             * @see Zend_Db_Adapter_Exception
             */
            require_once 'Zend/Db/Adapter/Exception.php';
            throw new Zend_Db_Adapter_Exception("Configuration array must have a key for 'password' for login credentials.");
        }

        if (! array_key_exists('username', $config)) {
            /**
             * @see Zend_Db_Adapter_Exception
             */
            require_once 'Zend/Db/Adapter/Exception.php';
            throw new Zend_Db_Adapter_Exception("Configuration array must have a key for 'username' for login credentials.");
        }
    }

    /**
     * Returns the underlying database connection object or resource.
     * If not presently connected, this initiates the connection.
     *
     * @return object|resource|null
     */
    public function getConnection()
    {
        $this->_connect();
        return $this->_connection;
    }

    /**
     * Returns the profiler for this adapter.
     *
     * @return Zend_Db_Profiler
     */
    public function getProfiler()
    {
        return $this->_profiler;
    }

    /**
     * Prepares and executes an SQL statement with bound data.
     *
     * @param  mixed  $sql  The SQL statement with placeholders.
     *                      May be a string or Zend_Db_Select.
     * @param  mixed  $bind An array of data to bind to the placeholders.
     * @return Zend_Db_Statement_Interface
     */
    public function query($sql, $bind = array())
    {
        // connect to the database if needed
        $this->_connect();

        // is the $sql a Zend_Db_Select object?
        if ($sql instanceof Zend_Db_Select) {
            $sql = $sql->__toString();
        }

        // make sure $bind to an array;
        // don't use (array) typecasting because
        // because $bind may be a Zend_Db_Expr object
        if (!is_array($bind)) {
            $bind = array($bind);
        }

        // prepare and execute the statement with profiling
        $stmt = $this->prepare($sql);
        $stmt->execute($bind);

        // return the results embedded in the prepared statement object
        $stmt->setFetchMode($this->_fetchMode);
        return $stmt;
    }

    /**
     * Leave autocommit mode and begin a transaction.
     *
     * @return bool True
     */
    public function beginTransaction()
    {
        $this->_connect();
        $q = $this->_profiler->queryStart('begin', Zend_Db_Profiler::TRANSACTION);
        $this->_beginTransaction();
        $this->_profiler->queryEnd($q);
        return true;
    }

    /**
     * Commit a transaction and return to autocommit mode.
     *
     * @return bool True
     */
    public function commit()
    {
        $this->_connect();
        $q = $this->_profiler->queryStart('commit', Zend_Db_Profiler::TRANSACTION);
        $this->_commit();
        $this->_profiler->queryEnd($q);
        return true;
    }

    /**
     * Roll back a transaction and return to autocommit mode.
     *
     * @return bool True
     */
    public function rollBack()
    {
        $this->_connect();
        $q = $this->_profiler->queryStart('rollback', Zend_Db_Profiler::TRANSACTION);
        $this->_rollBack();
        $this->_profiler->queryEnd($q);
        return true;
    }

    /**
     * Inserts a table row with specified data.
     *
     * @param mixed $table The table to insert data into.
     * @param array $bind Column-value pairs.
     * @return int The number of affected rows.
     */
    public function insert($table, array $bind)
    {
        // extract and quote col names from the array keys
        $cols = array();
        $vals = array();
        foreach ($bind as $col => $val) {
            $cols[] = $this->quoteIdentifier($col, true);
            if ($val instanceof Zend_Db_Expr) {
                $vals[] = $val->__toString();
                unset($bind[$col]);
            } else {
                $vals[] = '?';
            }
        }

        // build the statement
        $sql = "INSERT INTO "
             . $this->quoteIdentifier($table, true)
             . ' (' . implode(', ', $cols) . ') '
             . 'VALUES (' . implode(', ', $vals) . ')';

        // execute the statement and return the number of affected rows
        $stmt = $this->query($sql, array_values($bind));
        $result = $stmt->rowCount();
        return $result;
    }

    /**
     * Updates table rows with specified data based on a WHERE clause.
     *
     * @param  mixed        $table The table to update.
     * @param  array        $bind  Column-value pairs.
     * @param  mixed        $where UPDATE WHERE clause(s).
     * @return int          The number of affected rows.
     */
    public function update($table, array $bind, $where = '')
    {
        /**
         * Build "col = ?" pairs for the statement,
         * except for Zend_Db_Expr which is treated literally.
         */
        $set = array();
        foreach ($bind as $col => $val) {
            if ($val instanceof Zend_Db_Expr) {
                $val = $val->__toString();
                unset($bind[$col]);
            } else {
                $val = '?';
            }
            $set[] = $this->quoteIdentifier($col, true) . ' = ' . $val;
        }

        $where = $this->_whereExpr($where);

        /**
         * Build the UPDATE statement
         */
        $sql = "UPDATE "
             . $this->quoteIdentifier($table, true)
             . ' SET ' . implode(', ', $set)
             . (($where) ? " WHERE $where" : '');

        /**
         * Execute the statement and return the number of affected rows
         */
        $stmt = $this->query($sql, array_values($bind));
        $result = $stmt->rowCount();
        return $result;
    }

    /**
     * Deletes table rows based on a WHERE clause.
     *
     * @param  mixed        $table The table to update.
     * @param  mixed        $where DELETE WHERE clause(s).
     * @return int          The number of affected rows.
     */
    public function delete($table, $where = '')
    {
        $where = $this->_whereExpr($where);

        /**
         * Build the DELETE statement
         */
        $sql = "DELETE FROM "
             . $this->quoteIdentifier($table, true)
             . (($where) ? " WHERE $where" : '');

        /**
         * Execute the statement and return the number of affected rows
         */
        $stmt = $this->query($sql);
        $result = $stmt->rowCount();
        return $result;
    }

    /**
     * Convert an array, string, or Zend_Db_Expr object
     * into a string to put in a WHERE clause.
     *
     * @param mixed $where
     * @return string
     */
    protected function _whereExpr($where)
    {
        if (empty($where)) {
            return $where;
        }
        if (!is_array($where)) {
            $where = array($where);
        }
        foreach ($where as &$term) {
            if ($term instanceof Zend_Db_Expr) {
                $term = $term->__toString();
            }
            $term = '(' . $term . ')';
        }
        $where = implode(' AND ', $where);
        return $where;
    }

    /**
     * Creates and returns a new Zend_Db_Select object for this adapter.
     *
     * @return Zend_Db_Select
     */
    public function select()
    {
        return new Zend_Db_Select($this);
    }

    /**
     * Get the fetch mode.
     *
     * @return int
     */
    public function getFetchMode()
    {
        return $this->_fetchMode;
    }

    /**
     * Fetches all SQL result rows as a sequential array.
     * Uses the current fetchMode for the adapter.
     *
     * @param  string|Zend_Db_Select $sql  An SQL SELECT statement.
     * @param  mixed                 $bind Data to bind into SELECT placeholders.
     * @return array
     */
    public function fetchAll($sql, $bind = array())
    {
        $stmt = $this->query($sql, $bind);
        $result = $stmt->fetchAll($this->_fetchMode);
        return $result;
    }

    /**
     * Fetches all SQL result rows as an associative array.
     *
     * The first column is the key, the entire row array is the
     * value.
     *
     * @param string|Zend_Db_Select $sql An SQL SELECT statement.
     * @param mixed $bind Data to bind into SELECT placeholders.
     * @return string
     */
    public function fetchAssoc($sql, $bind = array())
    {
        $stmt = $this->query($sql, $bind);
        $data = array();
        while ($row = $stmt->fetch($this->_fetchMode)) {
            $tmp = array_values(array_slice($row, 0, 1));
            $data[$tmp[0]] = $row;
        }
        return $data;
    }

    /**
     * Fetches the first column of all SQL result rows as an array.
     *
     * The first column in each row is used as the array key.
     *
     * @param string|Zend_Db_Select $sql An SQL SELECT statement.
     * @param mixed $bind Data to bind into SELECT placeholders.
     * @return array
     */
    public function fetchCol($sql, $bind = array())
    {
        $stmt = $this->query($sql, $bind);
        $result = $stmt->fetchAll(Zend_Db::FETCH_COLUMN, 0);
        return $result;
    }

    /**
     * Fetches all SQL result rows as an array of key-value pairs.
     *
     * The first column is the key, the second column is the
     * value.
     *
     * @param string|Zend_Db_Select $sql An SQL SELECT statement.
     * @param mixed $bind Data to bind into SELECT placeholders.
     * @return string
     */
    public function fetchPairs($sql, $bind = array())
    {
        $stmt = $this->query($sql, $bind);
        $data = array();
        while ($row = $stmt->fetch(Zend_Db::FETCH_NUM)) {
            $data[$row[0]] = $row[1];
        }
        return $data;
    }

    /**
     * Fetches the first column of the first row of the SQL result.
     *
     * @param string|Zend_Db_Select $sql An SQL SELECT statement.
     * @param mixed $bind Data to bind into SELECT placeholders.
     * @return string
     */
    public function fetchOne($sql, $bind = array())
    {
        $stmt = $this->query($sql, $bind);
        $result = $stmt->fetchColumn(0);
        return $result;
    }

    /**
     * Fetches the first row of the SQL result.
     * Uses the current fetchMode for the adapter.
     *
     * @param string|Zend_Db_Select $sql An SQL SELECT statement.
     * @param mixed $bind Data to bind into SELECT placeholders.
     * @return array
     */
    public function fetchRow($sql, $bind = array())
    {
        $stmt = $this->query($sql, $bind);
        $result = $stmt->fetch($this->_fetchMode);
        return $result;
    }

    /**
     * Quote a raw string.
     *
     * @param string $value     Raw string
     * @return string           Quoted string
     */
    protected function _quote($value)
    {
        if (is_int($value) || is_float($value)) {
            return $value;
        }
        return "'" . addcslashes($value, "\000\n\r\\'\"\032") . "'";
    }

    /**
     * Safely quotes a value for an SQL statement.
     *
     * If an array is passed as the value, the array values are quoted
     * and then returned as a comma-separated string.
     *
     * @param mixed $value The value to quote.
     * @param mixed $type  OPTIONAL the SQL datatype name, or constant, or null.
     * @return mixed An SQL-safe quoted value (or string of separated values).
     */
    public function quote($value, $type = null)
    {
        $this->_connect();

        if ($value instanceof Zend_Db_Expr) {
            return $value->__toString();
        }

        if (is_array($value)) {
            foreach ($value as &$val) {
                $val = $this->quote($val, $type);
            }
            return implode(', ', $value);
        }

        if ($type !== null && array_key_exists($type = strtoupper($type), $this->_numericDataTypes)) {
            switch ($this->_numericDataTypes[$type]) {
                case Zend_Db::INT_TYPE: // 32-bit integer
                    return (string) intval($value);
                    break;
                case Zend_Db::BIGINT_TYPE: // 64-bit integer
                    if (preg_match('/^([1-9]\d*)/', (string) $value, $matches)) {
                        return $matches[1];
                    }
                    if (preg_match('/^(0x[\dA-F]+)/i', (string) $value, $matches)) {
                        return $matches[1];
                    }
                    if (preg_match('/^(0[0-7]*)/', (string) $value, $matches)) {
                        return $matches[1];
                    }
                    break;
                case Zend_Db::FLOAT_TYPE: // float or decimal
                    return (string) floatval($value);
                    break;
            }
            return '0';
        }

        return $this->_quote($value);
    }

    /**
     * Quotes a value and places into a piece of text at a placeholder.
     *
     * The placeholder is a question-mark; all placeholders will be replaced
     * with the quoted value.   For example:
     *
     * <code>
     * $text = "WHERE date < ?";
     * $date = "2005-01-02";
     * $safe = $sql->quoteInto($text, $date);
     * // $safe = "WHERE date < '2005-01-02'"
     * </code>
     *
     * @param string $text  The text with a placeholder.
     * @param mixed  $value The value to quote.
     * @param string $type  OPTIONAL SQL datatype
     * @return string An SQL-safe quoted value placed into the orignal text.
     */
    public function quoteInto($text, $value, $type = null)
    {
        return str_replace('?', $this->quote($value, $type), $text);
    }

    /**
     * Quotes an identifier.
     *
     * Accepts a string representing a qualified indentifier. For Example:
     * <code>
     * $adapter->quoteIdentifier('myschema.mytable')
     * </code>
     * Returns: "myschema"."mytable"
     *
     * Or, an array of one or more identifiers that may form a qualified identifier:
     * <code>
     * $adapter->quoteIdentifier(array('myschema','my.table'))
     * </code>
     * Returns: "myschema"."my.table"
     *
     * The actual quote character surrounding the identifiers may vary depending on
     * the adapter.
     *
     * @param string|array|Zend_Db_Expr $ident The identifier.
     * @param boolean $auto If true, heed the AUTO_QUOTE_IDENTIFIERS config option.
     * @return string The quoted identifier.
     */
    public function quoteIdentifier($ident, $auto=false)
    {
        return $this->_quoteIdentifierAs($ident, null, $auto);
    }

    /**
     * Quote a column identifier and alias.
     *
     * @param string|array|Zend_Db_Expr $ident The identifier or expression.
     * @param string $alias An alias for the column.
     * @param boolean $auto If true, heed the AUTO_QUOTE_IDENTIFIERS config option.
     * @return string The quoted identifier and alias.
     */
    public function quoteColumnAs($ident, $alias, $auto=false)
    {
        return $this->_quoteIdentifierAs($ident, $alias, $auto);
    }

    /**
     * Quote a table identifier and alias.
     *
     * @param string|array|Zend_Db_Expr $ident The identifier or expression.
     * @param string $alias An alias for the table.
     * @param boolean $auto If true, heed the AUTO_QUOTE_IDENTIFIERS config option.
     * @return string The quoted identifier and alias.
     */
    public function quoteTableAs($ident, $alias, $auto=false)
    {
        return $this->_quoteIdentifierAs($ident, $alias, $auto);
    }

    /**
     * Quote an identifier and an optional alias.
     *
     * @param string|array|Zend_Db_Expr $ident The identifier or expression.
     * @param string $alias An optional alias.
     * @param string $as The string to add between the identifier/expression and the alias.
     * @param boolean $auto If true, heed the AUTO_QUOTE_IDENTIFIERS config option.
     * @return string The quoted identifier and alias.
     */
    protected function _quoteIdentifierAs($ident, $alias = null, $auto = false, $as = ' AS ')
    {
        if ($ident instanceof Zend_Db_Expr) {
            $quoted = $ident->__toString();
        } else {
            if (is_string($ident)) {
                $ident = explode('.', $ident);
            }
            if (is_array($ident)) {
                $segments = array();
                foreach ($ident as $segment) {
                    if ($segment instanceof Zend_Db_Expr) {
                        $segments[] = $segment->__toString();
                    } else {
                        $segments[] = $this->_quoteIdentifier($segment, $auto);
                    }
                }
                if ($alias !== null && end($ident) == $alias) {
                    $alias = null;
                }
                $quoted = implode('.', $segments);
            } else {
                $quoted = $this->_quoteIdentifier($ident, $auto);
            }
        }
        if ($alias !== null) {
            $quoted .= $as . $this->_quoteIdentifier($alias, $auto);
        }
        return $quoted;
    }

    /**
     * Quote an identifier.
     *
     * @param  string $value The identifier or expression.
     * @param boolean $auto If true, heed the AUTO_QUOTE_IDENTIFIERS config option.
     * @return string        The quoted identifier and alias.
     */
    protected function _quoteIdentifier($value, $auto=false)
    {
        if ($auto === false || $this->_autoQuoteIdentifiers === true) {
            $q = $this->getQuoteIdentifierSymbol();
            return ($q . str_replace("$q", "$q$q", $value) . $q);
        }
        return $value;
    }

    /**
     * Returns the symbol the adapter uses for delimited identifiers.
     *
     * @return string
     */
    public function getQuoteIdentifierSymbol()
    {
        return '"';
    }

    /**
     * Return the most recent value from the specified sequence in the database.
     * This is supported only on RDBMS brands that support sequences
     * (e.g. Oracle, PostgreSQL, DB2).  Other RDBMS brands return null.
     *
     * @param string $sequenceName
     * @return integer
     */
    public function lastSequenceId($sequenceName)
    {
        return null;
    }

    /**
     * Generate a new value from the specified sequence in the database, and return it.
     * This is supported only on RDBMS brands that support sequences
     * (e.g. Oracle, PostgreSQL, DB2).  Other RDBMS brands return null.
     *
     * @param string $sequenceName
     * @return integer
     */
    public function nextSequenceId($sequenceName)
    {
        return null;
    }

    /**
     * Helper method to change the case of the strings used
     * when returning result sets in FETCH_ASSOC and FETCH_BOTH
     * modes.
     *
     * This is not intended to be used by application code,
     * but the method must be public so the Statement class
     * can invoke it.
     *
     * @param string $key
     * @returns string
     */
    public function foldCase($key)
    {
        switch ($this->_caseFolding) {
            case Zend_Db::CASE_LOWER:
                return strtolower((string) $key);
            case Zend_Db::CASE_UPPER:
                return strtoupper((string) $key);
            case Zend_Db::CASE_NATURAL:
            default:
                return (string) $key;
        }
    }

    /**
     * Abstract Methods
     */

    /**
     * Returns a list of the tables in the database.
     *
     * @return array
     */
    abstract public function listTables();

    /**
     * Returns the column descriptions for a table.
     *
     * The return value is an associative array keyed by the column name,
     * as returned by the RDBMS.
     *
     * The value of each array element is an associative array
     * with the following keys:
     *
     * SCHEMA_NAME => string; name of database or schema
     * TABLE_NAME  => string;
     * COLUMN_NAME => string; column name
     * COLUMN_POSITION => number; ordinal position of column in table
     * DATA_TYPE   => string; SQL datatype name of column
     * DEFAULT     => string; default expression of column, null if none
     * NULLABLE    => boolean; true if column can have nulls
     * LENGTH      => number; length of CHAR/VARCHAR
     * SCALE       => number; scale of NUMERIC/DECIMAL
     * PRECISION   => number; precision of NUMERIC/DECIMAL
     * UNSIGNED    => boolean; unsigned property of an integer type
     * PRIMARY     => boolean; true if column is part of the primary key
     * PRIMARY_POSITION => integer; position of column in primary key
     *
     * @param string $tableName
     * @param string $schemaName OPTIONAL
     * @return array
     */
    abstract public function describeTable($tableName, $schemaName = null);

    /**
     * Creates a connection to the database.
     *
     * @return void
     */
    abstract protected function _connect();

    /**
     * Force the connection to close.
     *
     * @return void
     */
    abstract public function closeConnection();

    /**
     * Prepare a statement and return a PDOStatement-like object.
     *
     * @param string|Zend_Db_Select $sql SQL query
     * @return Zend_Db_Statment|PDOStatement
     */
    abstract public function prepare($sql);

    /**
     * Gets the last ID generated automatically by an IDENTITY/AUTOINCREMENT column.
     *
     * As a convention, on RDBMS brands that support sequences
     * (e.g. Oracle, PostgreSQL, DB2), this method forms the name of a sequence
     * from the arguments and returns the last id generated by that sequence.
     * On RDBMS brands that support IDENTITY/AUTOINCREMENT columns, this method
     * returns the last value generated for such a column, and the table name
     * argument is disregarded.
     *
     * @param string $tableName   OPTIONAL Name of table.
     * @param string $primaryKey  OPTIONAL Name of primary key column.
     * @return integer
     */
    abstract public function lastInsertId($tableName = null, $primaryKey = null);

    /**
     * Begin a transaction.
     */
    abstract protected function _beginTransaction();

    /**
     * Commit a transaction.
     */
    abstract protected function _commit();

    /**
     * Roll-back a transaction.
     */
    abstract protected function _rollBack();

    /**
     * Set the fetch mode.
     *
     * @param integer $mode
     */
    abstract public function setFetchMode($mode);

    /**
     * Adds an adapter-specific LIMIT clause to the SELECT statement.
     *
     * @param mixed $sql
     * @param integer $count
     * @param integer $offset
     * @return string
     */
    abstract public function limit($sql, $count, $offset = 0);

    /**
     * Check if the adapter supports real SQL parameters.
     *
     * @param string $type 'positional' or 'named'
     * @return bool
     */
    abstract public function supportsParameters($type);

}
