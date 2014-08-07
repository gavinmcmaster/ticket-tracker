<?php
/**
 * Created by PhpStorm.
 * User: gavin
 * Date: 07/08/14
 * Time: 14:36
 */

require_once 'TruncateOperation.php';

abstract class Tracker_Tests_DatabaseTestCase extends PHPUnit_Extensions_Database_TestCase {
    // only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;

    final public function getConnection()
    {
       if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;

        /*$pdo = new PDO('sqlite::memory:');
        return $this->createDefaultDBConnection($pdo, ':memory:');*/
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