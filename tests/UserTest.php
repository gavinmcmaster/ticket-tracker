<?php

require_once 'Tracker_Tests_DatabaseTestCase.php';
require_once '../models/User.php';

class UserTest extends Tracker_Tests_DatabaseTestCase {

    public function testAddUser() {

        $this->assertEquals(7, $this->conn->getRowCount('users'), "Initial row count incorrect!");

        $user = new \User(self::$db);
        $user->insert('newUser', 'new@test.com', 'hello', 3);

         $this->assertEquals(8, $this->conn->getRowCount('users'), "Add user failed");
    }

     public function testAddUserData() {

        $user = new \User(self::$db);
        $user->insert('newUser', 'new@test.com', 'hello', 3);

        $expected = $this->createMySQLXMLDataSet('_files/expected_users.xml');
        $actual = new PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->conn);
        $actual->addTable('users');
        $this->assertDataSetsEqual($expected, $actual);
    }

    public function testProgrammerAdminUsers()
    {
        $queryTable = $this->conn->createQueryTable(
            'programmerAdmins', 'SELECT id, name, email FROM users WHERE user_type_id=2 AND permission_type_id=4'
        );
        $expectedTable = $this->createFlatXmlDataSet("_files/programmer_admins.xml")->getTable("programmerAdmins");
        $this->assertTablesEqual($expectedTable, $queryTable);
    }
}