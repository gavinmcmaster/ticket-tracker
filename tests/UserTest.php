<?php

require_once 'Tracker_Tests_DatabaseTestCase.php';

class UserTest extends Tracker_Tests_DatabaseTestCase {

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet() {
        return $this->createMySQLXMLDataSet('ticket_tracker.xml');
    }

    public function testGetRowCount() {
       $this->assertEquals(7, $this->getConnection()->getRowCount('users'));
    }

    //public function testAddUser
}