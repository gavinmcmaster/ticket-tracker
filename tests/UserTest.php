<?php

require_once 'Tracker_Tests_DatabaseTestCase.php';
require_once '../models/User.php';

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

    public function testAddUser() {
        $this->assertEquals(7, $this->getConnection()->getRowCount('users'));

        $user = new User($this->getConnection());
        //$user->addEntry("bob", "bob@test.com", "bobsPASSWORD", 1);
        $user->insert("bob", "bob@test.com", "bobsPASSWORD", 1);

        $this->assertEquals(8, $this->getConnection()->getRowCount('users'), "Add user failed");
    }
}