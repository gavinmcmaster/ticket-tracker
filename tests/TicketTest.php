<?php

require_once 'Tracker_Tests_DatabaseTestCase.php';
require_once '../models/Ticket.php';

class TicketTest extends Tracker_Tests_DatabaseTestCase {

    public function testAddTicket() {

        $this->assertEquals(18, $this->conn->getRowCount('tickets'), "Initial row count incorrect!");

        $ticket = new \Ticket(self::$db);
        $ticket->insert(3, 'new ticket', 'description', 11, 2 ,13);

        $this->assertEquals(19, $this->conn->getRowCount('tickets'), "Add ticket failed");
    }

    public function testTicketsWithComments() {
        $queryTable = $this->conn->createQueryTable(
            'ticketsWithComments', 'SELECT DISTINCT tickets.id, tickets.title FROM tickets INNER JOIN comments ON tickets.id = comments.ticket_id;'
        );
        $expectedTable = $this->createFlatXmlDataSet("_files/expected_tickets_with_comments.xml")->getTable("ticketsWithComments");
        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testTicketsWithAttachments() {
        $queryTable = $this->conn->createQueryTable(
            'ticketsWithAttachments', 'SELECT DISTINCT tickets.id, tickets.title FROM tickets INNER JOIN attachments ON tickets.id = attachments.ticket_id;'
        );
        $expectedTable = $this->createFlatXmlDataSet("_files/expected_tickets_with_attachments.xml")->getTable("ticketsWithAttachments");
        $this->assertTablesEqual($expectedTable, $queryTable);
    }
}