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
        echo "Controller,handleAction " .$action . "<br/>";
        switch($action) {
            case "login":
                $this->login();
                break;
            case "register":
                $this->register();
                break;
            case "logout":
                $this->logout();
               break;
            case "listTickets":
                $this->listTickets();
                break;
            case "createTicket":
                $create = isset($_POST['create']);
                $this->createTicket($create);
                break;
            case "viewTicket":
                $ticketId = $_GET['id'];

                //echo "userFile isset: " . isset($_FILES['userFile']) . " - " . count($_FILES);

                if(isset($_FILES['file'])) {
                    if ($_FILES["file"]["error"] > 0) {
                        echo "File upload error: " . $_FILES["file"]["error"] . "<br>";
                    }
                    else{
                        $this->handleFileUpload($ticketId, $_FILES);
                    }
                }

                $modify = isset($_POST['modify']);
                $resolve = isset($_POST['resolve']);
                $reopen = isset($_POST['reopen']);
                $editComment = isset($_POST['editComment']) ? $_POST['editComment'] : null;
                $updateComment = isset($_POST['submit_comment_edit']) ? $_POST['submit_comment_edit'] : null;

                //echo "edit comment: " . $editComment . "<br/>";
                //echo "ticket " . $ticketId . ", modify " . $modify . ", resolve " . $resolve . ", reopen " . $reopen . "<br/>";

                if($modify) {
                    //echo "modify ticket before displaying"."<br/>";
                    $assignToId = (!empty($_POST['assignTo']))? (int)$_POST['assignTo'] : null;
                    $ticketTypeId = (!empty($_POST['ticketType']))? (int)$_POST['ticketType'] : null;
                    $priorityTypeId = (!empty($_POST['priorityType']))? (int)$_POST['priorityType'] : null;
                    $this->modifyTicket($ticketId, $assignToId, $ticketTypeId, $priorityTypeId);
                }

                if($resolve) {
                    $resolutionId = (!empty($_POST['resolutionType']))? (int)$_POST['resolutionType'] : null;
                    $this->resolveTicket($ticketId, $resolutionId);
                }

                if($reopen) {
                    $this->reopenTicket($ticketId);
                }

                $createComment = isset($_GET['addComment']) ? $_GET['addComment'] : null;
                $commentInput = (!empty($_POST['commentInput']) && (!isset($_POST['cancel_edit']))) ? $_POST['commentInput'] : null;
                $this->viewTicket($ticketId, $createComment, $commentInput, $editComment, $updateComment);
                break;
            case "deleteTicket":
                $ticketId = $_GET['id'];
                $this->deleteTicket($ticketId);
                break;
            default:
                echo "error, the action specified is not valid";
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
                    ///cho "Controller register user " . $user['id']. " - " .$user['name'] . " - " . $user['email'] . " - ". $user['user_type_id'] . " - " . $user['permission_type_id'];
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
        Session::getInstance()->__unset('user_permission_type');
        //Session::getInstance()->__unset('permission_type_id');
        Session::getInstance()->destroy();

        $url = 'http://ticket_tracker.local/index.php';
        header("Location: $url");
        die();
    }

    public function listTickets() {
        //echo "Controller listTickets";
        try {
            $allTickets = $this->ticketController->fetchAllTickets();
        }
        catch(Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        include __DIR__ . '/../templates/list_all_tickets.php';
    }

    public function createTicket($create) {
        //echo "Controller createTicket<br/>";
        
        if($create) {
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
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        include __DIR__ . '/../templates/create_ticket.php';
    }

    public function viewTicket($ticketId, $createComment, $commentInput, $editComment, $updateComment) {
        //echo "Controller viewTicket ".$ticketId;
        $ticket = $this->ticketController->getTicketById($ticketId);
        $ticketTypeData = $this->ticketController->getTicketTypeById($ticket['type_id']);
        $ticketType = $ticketTypeData['type'];
        $priorityTypeData = $this->ticketController->getPriorityTypeById($ticket['priority_type_id']);
        $priorityType = $priorityTypeData['type'];
        $assigneeData = $this->userController->getUserById($ticket['assigned_to_id']);
        $reporterData = $this->userController->getUserById($ticket['reported_by_id']);
        $assignee = $assigneeData['name'];
        $reporter = $reporterData['name'];
        $dateCreated = date_create($ticket['created_time']);
        $dateResolved = (isset($ticket['resolved_time'])) ? date_create($ticket['resolved_time']) : "";
        $userPermissionType = Session::getInstance()->__get('user_permission_type');
        echo "permission type: " . $userPermissionType ."<br/>"; // admin, crud, update, view

        if(isset($commentInput) && !empty($commentInput)) {
            echo "comment input is set to: " .  $commentInput;
            try {
               $success = $this->ticketController->addComment($ticketId, $commentInput);
            }
            catch(Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        }

        $allCommentsData = $this->ticketController->getTicketComments($ticketId);
        $allAttachmentsData = $this->ticketController->getTicketAttachments($ticketId);

        echo "there are " .count($allCommentsData) . " tickets";

        include __DIR__ . '/../templates/view_ticket.php';
    }

    /*public function editTicket() {
        echo "Controller editTicket";
    }*/

    public function deleteTicket($ticketId) {
        echo "Controller deleteTicket: ".$ticketId;
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
        $userPermissionTypeData = $this->userController->getUserPermissionTypeById($user['permission_type_id']);
        Session::getInstance()->__set('user_permission_type', $userPermissionTypeData['type']);
        // not sure this is needed, or even useful
        //Session::getInstance()->__set('permission_type_id', $user['permission_type_id']);

        $url = 'http://ticket_tracker.local/index.php?action=listTickets';
        header("Location: $url");
        die();
    }

    private function handleFileUpload($ticketId, $files) {
       //echo "Controller handleFileUpload " . $files['file']['name'] . "<br/>";

        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $allowedFileTypes = array("image/gif", "image/jpeg", "image/jpg", "image/x-png", "image/png");
        $temp = explode(".", $files["file"]["name"]);
        $extension = end($temp);
        $fileType = $files["file"]["type"];
        $filePath = ATTACHMENTS_UPLOAD_DIRECTORY . $files["file"]["name"];

        if (in_array($fileType, $allowedFileTypes) && in_array($extension, $allowedExts)) {
          if ($files["file"]["error"] > 0) {
            echo "Error Return Code: " . $files["file"]["error"] . "<br>";
          } else {
            /*echo "Upload: " . $files["file"]["name"] . "<br>";
            echo "Type: " . $files["file"]["type"] . "<br>";
            echo "Size: " . ($files["file"]["size"] / 1024) . " kB<br>";
            echo "Temp file: " . $files["file"]["tmp_name"] . "<br>";*/

            if (file_exists($filePath)) {
              echo $filePath . " already exists. ";
            } else {
              $fileStored = move_uploaded_file($files["file"]["tmp_name"], $filePath);
              
              if($fileStored) {
                try {
                    $success = $this->ticketController->addAttachment($ticketId, $filePath);
                }
                catch(Exception $e) {
                     echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            }
              
            }
          }
        } else {
          echo "Invalid file";
        }
    }
}
