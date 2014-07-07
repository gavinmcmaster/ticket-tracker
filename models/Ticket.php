<?php
/**
 * Created by PhpStorm.
 * User: gavin
 * Date: 13/06/14
 * Time: 16:21
 */

class Ticket {

    private $dbo;

    public function __construct($dbObject) {
        $this->dbo = $dbObject;
    }

    public function fetchAllTickets() {
        $this->dbo->query("SELECT * FROM tickets");
        $this->dbo->execute();
        $result = $this->dbo->fetchAll();

        //echo "Ticket " . count($result);

        return $result;
    }

    public function fetchTicketTypes() {
        $this->dbo->query("SELECT * FROM ticket_types");
        $this->dbo->execute();
        $result = $this->dbo->fetchAll();

        //echo "Ticket types" . count($result);

        return $result;
    }

    public function fetchPriorityTypes() {
        $this->dbo->query("SELECT * FROM ticket_priority_types");
        $this->dbo->execute();
        $result = $this->dbo->fetchAll();

        //echo "Ticket types" . count($result);

        return $result;
    }

    public function insert($type_id, $title, $description, $reportedById, $ticketPriorityTypeId, $assignedToId) {
        //echo "Ticket insert ".$type_id." - ".$title." - ".$description." - ".$reportedById." - " .$ticketPriorityTypeId." - ".$assignedToId;
        //  echo "<br/>";

        $columns = array('type_id', 'title', 'description', 'reported_by_id', 'priority_type_id', 'created_time');
        $values = array(':type_id', ':title', ':description', ':reported_by_id', ':priority_type_id', 'NOW()');

        if(isset($assignedToId)) {
            array_push($columns, 'assigned_to_id', 'status_type_id');
            array_push($values, ':assigned_to_id', ':status_type_id');
            $status = $this->getStatusTypeByName('assigned');
            $statusTypeId = $status['id'];
        }

        $c = implode(', ', $columns);
        $v = implode(', ', $values);
        $sql = "INSERT INTO tickets (".$c.") VALUES (".$v.")";
        //echo $sql;
        //die();
        $this->dbo->query($sql);
        $this->dbo->bind(':type_id', $type_id);
        $this->dbo->bind(':title', $title);
        $this->dbo->bind(':description', $description);
        $this->dbo->bind(':reported_by_id', $reportedById);
        $this->dbo->bind(':priority_type_id', $ticketPriorityTypeId);
        if(isset($assignedToId)) {
            $this->dbo->bind(':assigned_to_id', $assignedToId);
            $this->dbo->bind(':status_type_id', $statusTypeId);
        }
        $success = $this->dbo->execute();

        //echo "insert success : ".$success."<br/>";

        return $success;
    }

    public function delete() {

    }

    public function getLastInsertId() {
        return $this->dbo->lastInsertId();
    }

    public function getTicketById($id) {
        $this->dbo->query("SELECT * FROM tickets WHERE id = :id");
        $this->dbo->bind(':id', $id);
        $this->dbo->execute();
        $result = $this->dbo->fetch();

        return $result;
    }

    public function getTicketTypeById($id) {
        $this->dbo->query("SELECT * FROM ticket_types WHERE id = :id");
        $this->dbo->bind(':id', $id);
        $this->dbo->execute();
        $result = $this->dbo->fetch();

        return $result;
    }

    public function getStatusTypeById($id) {
        $this->dbo->query("SELECT * FROM ticket_status_types WHERE id = :id");
        $this->dbo->bind(':id', $id);
        $this->dbo->execute();
        $result = $this->dbo->fetch();

        return $result;
    }

    public function getPriorityTypeById($id) {
        $this->dbo->query("SELECT * FROM ticket_priority_types WHERE id = :id");
        $this->dbo->bind(':id', $id);
        $this->dbo->execute();
        $result = $this->dbo->fetch();

        return $result;
    }

    private function getStatusTypeByName($name) {
        $this->dbo->query("SELECT * FROM ticket_status_types WHERE type = :type");
        $this->dbo->bind(':type', $name);
        $this->dbo->execute();
        $result = $this->dbo->fetch();

        return $result;
    }
} 