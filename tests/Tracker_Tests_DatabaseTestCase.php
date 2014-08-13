<?php
/**
 * Created by PhpStorm.
 * User: gavin
 * Date: 07/08/14
 * Time: 14:36
 */

require_once 'TruncateOperation.php';
require_once '../models/Database.php';

abstract class Tracker_Tests_DatabaseTestCase extends PHPUnit_Extensions_Database_TestCase {
    // only instantiate pdo once for test clean-up/fixture load
    // both need to be static to persist throughout all tests
    static protected $pdo = null;
    static protected $db = null;

    // instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    protected $conn = null;

    final public function getConnection()
    {
       if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$db = new Database('localhost', $GLOBALS['DB_DBNAME'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
                self::$pdo = self::$db->getPDO();
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet() {
        return $this->createMySQLXMLDataSet('_files/ticket_tracker.xml');
    }

    public function getSetUpOperation()
    {
        $cascadeTruncates = false; // If you want cascading truncates, false otherwise. If unsure choose false.

        return new PHPUnit_Extensions_Database_Operation_Composite(array(
            new TruncateOperation($cascadeTruncates),
            PHPUnit_Extensions_Database_Operation_Factory::INSERT()
        ));
    }
} 