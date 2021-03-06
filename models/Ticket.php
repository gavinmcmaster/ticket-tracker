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

    public function fetchResolutionTypes() {
        $this->dbo->query("SELECT * FROM ticket_resolution_types");
        $this->dbo->execute();
        $result = $this->dbo->fetchAll();

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

    public function getResolutionTypeById($id) {
        $this->dbo->query("SELECT * FROM ticket_resolution_types WHERE id = :id");
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

    public function getTicketComments($ticketId) {
        $this->dbo->query("SELECT * FROM comments WHERE ticket_id = :ticket_id");
        $this->dbo->bind(':ticket_id', $ticketId);
        $this->dbo->execute();
        $result = $this->dbo->fetchAll();

        return $result;
    }

    public function addComment($ticketId, $comment) {
        echo "Ticket,addComment " .$ticketId . " - " .$comment . "<br/>";
        $this->dbo->query("INSERT INTO comments (ticket_id, comment, created_time, added_by_id) VALUES (:ticket_id, :comment, NOW(), :added_by_id)");
        $this->dbo->bind(':ticket_id', $ticketId);
        $this->dbo->bind(':comment', $comment);
        $this->dbo->bind(':added_by_id', Session::getInstance()->__get('user_id'));
        $success = $this->dbo->execute();

        return $success;
    }

    public function getTicketAttachments($ticketId) {
        $this->dbo->query("SELECT * FROM attachments WHERE ticket_id = :ticket_id");
        $this->dbo->bind(':ticket_id', $ticketId);
        $this->dbo->execute();
        $result = $this->dbo->fetchAll();

        return $result;
    }

    public function addAttachment($ticketId, $path, $type) {
        $this->dbo->query("INSERT INTO attachments (ticket_id, filepath, added_time, added_by_id, file_type) VALUES (:ticket_id, :filepath, NOW(), :added_by_id, :file_type)");
        $this->dbo->bind(':ticket_id', $ticketId);
        $this->dbo->bind(':filepath', $path);
        $this->dbo->bind(':file_type', $type);
        $this->dbo->bind(':added_by_id', Session::getInstance()->__get('user_id'));
        $success = $this->dbo->execute();

        return $success;
    }

    public function setUpdatedTime($ticketId) {
        $this->dbo->query("UPDATE tickets SET updated_time = NOW() WHERE id = :ticket_id");
        $this->dbo->bind(':ticket_id', $ticketId);
        $success = $this->dbo->execute();

        return $success;
    }

    public function setResolved($ticketId, $resolutionId) {
        $this->dbo->query("UPDATE tickets SET resolution_type_id = :resolution_id WHERE id = :ticket_id");
        $this->dbo->bind(':ticket_id', $ticketId);
        $this->dbo->bind(':resolution_id', $resolutionId);
        $success = $this->dbo->execute();

        return $success;
    }

    public function setResolvedTime($ticketId) {
        $this->dbo->query("UPDATE tickets SET resolved_time = NOW() WHERE id = :ticket_id");
        $this->dbo->bind(':ticket_id', $ticketId);
        $success = $this->dbo->execute();

        return $success;
    }

    public function setUnresolved($ticketId) {
        $this->dbo->query("UPDATE tickets SET resolution_type_id = null WHERE id = :ticket_id");
        $this->dbo->bind(':ticket_id', $ticketId);
        $success = $this->dbo->execute();

        return $success;
    }

    public function unsetResolvedTime($ticketId) {
        $this->dbo->query("UPDATE tickets SET resolved_time = null WHERE id = :ticket_id");
        $this->dbo->bind(':ticket_id', $ticketId);
        $success = $this->dbo->execute();

        return $success;
    }

    public function setAssignedTo($ticketId, $userId) {
        $this->dbo->query("UPDATE tickets SET assigned_to_id = :assigned_to_id WHERE id = :ticket_id");
        $this->dbo->bind(':assigned_to_id', $userId);
        $this->dbo->bind(':ticket_id', $ticketId);
        $success = $this->dbo->execute();

        return $success;
    }

    public function setTicketType($ticketId, $typeId) {
        $this->dbo->query("UPDATE tickets SET type_id = :type_id WHERE id = :ticket_id");
        $this->dbo->bind(':type_id', $typeId);
        $this->dbo->bind(':ticket_id', $ticketId);
        $success = $this->dbo->execute();

        return $success;
    }

    public function setPriorityType($ticketId, $priorityTypeId) {
        $this->dbo->query("UPDATE tickets SET priority_type_id = :priority_type_id WHERE id = :ticket_id");
        $this->dbo->bind(':ticket_id', $ticketId);
        $this->dbo->bind(':priority_type_id', $priorityTypeId);
        $success = $this->dbo->execute();

        return $success;
    }

    public function updateComment($commentId, $comment) {
        $this->dbo->query("UPDATE comments SET comment = :comment WHERE id= :comment_id");
        $this->dbo->bind(':comment', $comment);
        $this->dbo->bind(':comment_id', $commentId);
        $success = $this->dbo->execute();

        return $success;
    }

    public function setCommentUpdatedTime($commentId) {
        $this->dbo->query("UPDATE comments SET edited_time = NOW() WHERE id = :comment_id");
        $this->dbo->bind(':comment_id', $commentId);
        $success = $this->dbo->execute();

        return $success;
    }

    public function setCommentUpdatedBy($commentId, $userId) {
        $this->dbo->query("UPDATE comments SET last_edited_by_id = :user_id WHERE id = :comment_id");
        $this->dbo->bind(':user_id', $userId);
        $this->dbo->bind(':comment_id', $commentId);
        $success = $this->dbo->execute();

        return $success;
    }

    public function getAttachmentPathById($id) {
        $this->dbo->query("SELECT * FROM attachments WHERE id = :id");
        $this->dbo->bind(':id', $id);
        $this->dbo->execute();
        $result = $this->dbo->fetch();

        return $result;
    }
}