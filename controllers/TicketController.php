<?php
/**
 * Created by PhpStorm.
 * User: gavin
 * Date: 26/06/14
 * Time: 09:37
 */

class TicketController {

    private $dbo = null;
    private $ticket;

    public function __construct($dbObject) {
        $this->dbo = $dbObject;
        $this->init();
    }

    private function init() {
        //echo 'TicketController init';
        if(is_null($this->ticket)) $this->ticket = new Ticket($this->dbo);
    }

    public function createTicket() {
        if(isset($_POST['createTicketType'])) $ticketTypeId = $_POST['createTicketType'];
        if(isset($_POST['createTicketInputTitle'])) $title = $_POST['createTicketInputTitle'];
        if(isset($_POST['createTicketInputDescription'])) $description = $_POST['createTicketInputDescription'];
        //if(isset($_POST['createTicketInputReporter'])) $reportedBy = $_POST['createTicketInputReporter'];
        if(isset($_POST['createPriorityType'])) $ticketPriorityTypeId = $_POST['createPriorityType'];
        if(isset($_POST['createAssignUser'])) $assignedToId = $_POST['createAssignUser'] !== "null" ? $_POST['createAssignUser'] : null;
        $reportedById = Session::getInstance()->__get('user_id');

        if(isset($ticketTypeId) && isset($title) && isset($description) && isset($reportedById) && isset($ticketPriorityTypeId)){
           if(!$assignedToId && !isset($assignedToId)) $assignedToId = null;

           return $this->ticket->insert($ticketTypeId, $title, $description, $reportedById, $ticketPriorityTypeId, $assignedToId);
        }
    }

    public function addComment($ticketId, $comment) {
        return $this->ticket->addComment($ticketId, $comment);
    }

    public function getTicketComments($id) {
        return $this->ticket->getTicketComments($id);
    }

    public function fetchAllTickets() {
        return $this->ticket->fetchAllTickets();
    }

    public function fetchTicketTypes() {
        return $this->ticket->fetchTicketTypes();
    }

    public function fetchPriorityTypes() {
        return $this->ticket->fetchPriorityTypes();

    }

    public function getLastInsertId() {
        return $this->ticket->getLastInsertId();
    }

    public function getTicketById($id) {
        return $this->ticket->getTicketById($id);
    }

    public function getTicketTypeById($id) {
        return $this->ticket->getTicketTypeById($id);
    }

    public function getStatusTypeById($id) {
        return $this->ticket->getStatusTypeById($id);
    }

    public function getPriorityTypeById($id) {
        return $this->ticket->getPriorityTypeById($id);
    }
} 