<?php
/**
 * Created by PhpStorm.
 * User: gavin
 * Date: 05/06/14
 * Time: 12:30
 */

//namespace controllers;

class Controller {

    private $dbo;
    private $db_host = "localhost";
    private $db_name = "ticket_tracker"; 
    private $db_user = "****";
    private $db_pass = "*****";

    private $userController = null;
    private $ticketController = null;
    private $config = null;

    public function __construct($configObject) {
        //echo "Controller constructor";
        $this->config = $configObject;
        $this->init();
     }

    private function init() {
        //echo "Controller init";
        $this->dbo = new Database($this->db_host, $this->db_name, $this->db_user, $this->db_pass);

        if($this->dbo->getIsConnected()) {
            //echo "db is connected";
            if(is_null($this->userController)) $this->userController = new UserController($this->dbo, $this->config);
            if(is_null($this->ticketController)) $this->ticketController = new TicketController($this->dbo);
        }
    }

    public function handleAction($action) {

        switch($action) {


        }
    }

    public function login() {
        //echo "Controller login";
        try {
            $user = $this->userController->login();
            if($user) {
                //echo "Controller login user " . $user['id']. " - " .$user['name'] . " - " . $user['email'] . " - ". $user['user_type_id'] . " - " . $user['permission_type_id'];
                $this->handleUserVerification($user);
            }
            else {
                echo "Controller login failed!!";
            }
        } catch(Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function register() {
        //echo "Controller register";
        try {
            $success = $this->userController->register();
            if($success){
                
                $userId = $this->userController->getLastInsertId();
                $user = $this->userController->getUserById($userId);

                if($user) {
                    //echo "Controller register user " . $user['id']. " - " .$user['name'] . " - " . $user['email'] . " - ". $user['user_type_id'] . " - " . $user['permission_type_id'];
                    $this->handleUserVerification($user);
                }
            }
        } catch(Exception $e) {
             echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function logout() {
        echo "Controller logout call Session destroy";

        Session::getInstance()->__unset('user_id');
        Session::getInstance()->__unset('user_name');
        Session::getInstance()->__unset('user_email');
        Session::getInstance()->__unset('user_type_id');
        Session::getInstance()->__unset('permission_type_id');
        Session::getInstance()->destroy();

        $url = 'http://ticket_tracker.local/index.php';
        header("Location: $url");
        die();
    }

    public function listTickets() {
        //echo "Controller listTickets";
        try {
            $allTickets = $this->ticketController->fetchAllTickets();
            //$users = $this->userController()->fetchAllUsers();

        }
        catch(Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        include __DIR__ . '/../templates/list_all_tickets.php';
    }

    public function createTicket() {
        //echo "Controller createTicket<br/>";
        
        if(isset($_POST['create'])) {
            try {
                $success = $this->ticketController->createTicket();
                echo "createTicket success: " . $success;
                if($success) {
                    $ticketId = $this->ticketController->getLastInsertId();
                    $url = 'http://ticket_tracker.local/index.php?action=viewTicket?id='.$ticketId;
                    header("Location: $url");
                    die();
                }
            }
            catch(Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }

            return;
        }

        try {
            $ticketTypes = $this->ticketController->fetchTicketTypes();
            $ticketPriorityTypes = $this->ticketController->fetchPriorityTypes();
            $allUsers = $this->userController->fetchAllUsers();
        }
        catch(Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        include __DIR__ . '/../templates/create_ticket.php';
    }

    public function viewTicket() {
        $ticketId = $_GET['id'];
        //echo "Controller viewTicket ".$ticketId;
        $ticket = $this->ticketController->getTicketById($ticketId);
        $ticketTypeData = $this->ticketController->getTicketTypeById($ticket['type_id']);
        $ticketType = $ticketTypeData['type'];
        $priorityTypeData = $this->ticketController->getPriorityTypeById($ticket['priority_type_id']);
        $priorityType = $priorityTypeData['type'];


        include __DIR__ . '/../templates/view_ticket.php';
    }

    /*public function editTicket() {
        echo "Controller editTicket";
    }*/

    public function deleteTicket() {
        echo "Controller deleteTicket";
    }

    public function getUserController() {
        return $this->userController;
    }

    public function getTicketController() {
        return $this->ticketController;
    }

    private function handleUserVerification($user) {
        Session::getInstance()->__set('user_id', $user['id']);
        Session::getInstance()->__set('user_name', $user['name']);
        Session::getInstance()->__set('user_email', $user['email']);
        Session::getInstance()->__set('user_type_id', $user['user_type_id']);
        Session::getInstance()->__set('permission_type_id', $user['permission_type_id']);

        $url = 'http://ticket_tracker.local/index.php?action=listTickets';
        header("Location: $url");
        die();
    }
}
