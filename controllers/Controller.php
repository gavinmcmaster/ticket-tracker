<?php
/**
 * Created by PhpStorm.
 * User: gavin
 * Date: 05/06/14
 * Time: 12:30
 */

class Controller extends MainController {

    public function handleAction($action) {

        //echo "Controller, handleAction " .$action;

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
            case "uploadFile":
                $ticketId = $_GET['id'];

                echo "userFile isset: " . isset($_FILES['userFile']) . " - " . count($_FILES);
                if(isset($_FILES['file'])) {
                    if ($_FILES["file"]["error"] > 0) {
                        echo "File upload error: " . $_FILES["file"]["error"] . "<br>";
                    }
                    else{
                        $this->uploadFile($ticketId, $_FILES);
                    }
                }
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
            $userPermissionTypeId = Session::getInstance()->__get('permission_type_id');
            if($userPermissionTypeId != USER_PERMISSION_VIEW) {
                $allTickets = $this->ticketController->fetchAllTickets();
            }
            else {
                $userId = Session::getInstance()->__get('user_id');
                $allTickets = $this->ticketController->fetchAllTicketsAssignedToUser($userId);
            }
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
                    $url = 'http://ticket_tracker.local/index.php?action=viewTicket&id='.$ticketId;
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
        $timeCreated = date_create($ticket['created_time']);
        $timeResolved = (isset($ticket['resolved_time'])) ? date_create($ticket['resolved_time']) : "";
        $userPermissionTypeId = Session::getInstance()->__get('permission_type_id');
        //echo "permission type: " . $userPermissionTypeId ."<br/>"; // admin, crud, update, view

        $resolutionTypes = $this->ticketController->fetchResolutionTypes();
        $allUsers = $this->userController->fetchAllUsers();
        $ticketTypes = $this->ticketController->fetchTicketTypes();
        $priorityTypes = $this->ticketController->fetchPriorityTypes();
        $ticketIsResolved = isset($ticket['resolution_type_id']);

        if($ticketIsResolved) {
            try {
                $resolvedAsData =  $this->ticketController->getResolutionTypeById($ticket['resolution_type_id']);
                $resolvedAs = $resolvedAsData['type'];
            }
            catch(Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        }

        //echo "comment input is set: " .  isset($commentInput) . " - " . isset($editComment);
        if(isset($commentInput) && !empty($commentInput)) {

            if(!$updateComment) {
                try {
                    $commentAdded = $this->ticketController->addComment($ticketId, $commentInput);
                    if($commentAdded) {
                        $ticketTimeUpdated = $this->ticketController->setUpdatedTime($ticketId);
                    }
                    // need to pull down the ticket info again
                    if(isset($ticketTimeUpdated) && $ticketTimeUpdated) {
                        $ticket = $this->ticketController->getTicketById($ticketId);
                    }
                }
                catch(Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            }
            else {
                try {
                    $updateCommentId = (int)$updateComment;
                    $commentUpdated = $this->ticketController->updateComment($updateCommentId, $commentInput);
                    if($commentUpdated) {
                        $userId = Session::getInstance()->__get('user_id');
                        $commentTimeUpdated = $this->ticketController->setCommentUpdatedTime($updateCommentId);
                        $commentUpdatedBy = $this->ticketController->setCommentUpdatedBy($updateCommentId, $userId);
                        $ticketTimeUpdated = $this->ticketController->setUpdatedTime($ticketId);
                    }
                    // need to pull down the ticket info again
                    if(isset($ticketTimeUpdated) && $ticketTimeUpdated) {
                        $ticket = $this->ticketController->getTicketById($ticketId);
                    }
                }
                catch(Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            }
        }

        if(isset($editComment)) {
            $editCommentId = (int)$editComment;
        }

        $timeUpdated = (isset($ticket['updated_time'])) ? date_create($ticket['updated_time']) : "";

        $allCommentsData = $this->ticketController->getTicketComments($ticketId);
        $allAttachmentsData = $this->ticketController->getTicketAttachments($ticketId);

        if(isset($editComment)) {   
            $editComment = (int)$editComment;
        }

        if(isset($_POST['submit_comment_edit'])) {
            echo "edit comment with id: " . $_POST['submit_comment_edit'];
        }

        include __DIR__ . '/../templates/view_ticket.php';
    }

    public function modifyTicket($ticketId, $assignToId, $ticketTypeId, $priorityTypeId) {
        //echo "Controller modifyTicket: ".$ticketId;
        $updated = false;

        if(is_int($assignToId) && $assignToId > 0) {
            try {
                $assignToSet = $this->ticketController->setAssignedTo($ticketId, $assignToId);
                if($assignToSet) {
                    $updated = true;
                }
            }
            catch(Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        }

        if(is_int($ticketTypeId) && $ticketTypeId > 0) {
            try {
                $ticketTypeSet = $this->ticketController->setTicketType($ticketId, $ticketTypeId);
                if($ticketTypeSet) {
                    $updated = true;
                }
            }
            catch(Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        }

        if(is_int($priorityTypeId) && $priorityTypeId > 0) {
            try {
                $priorityTypeSet = $this->ticketController->setPriorityType($ticketId, $priorityTypeId);
                if($ticketTypeSet) {
                    $updated = true;
                }
            }
            catch(Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        }
    }

    public function resolveTicket($ticketId, $resolutionId) {
        echo "Controller resolveTicket ". $ticketId . " - " . $resolutionId;

        if(is_int($resolutionId) && $resolutionId > 0) {

            try {
                $success = $this->ticketController->setResolved($ticketId, $resolutionId);
                if($success) {

                    try {
                        $ticketUpdatedTime = $this->ticketController->setUpdatedTime($ticketId);
                        $ticketResolvedTime = $this->ticketController->setResolvedTime($ticketId);
                    }
                    catch(Exception $e) {
                        echo 'Caught exception: ',  $e->getMessage(), "\n";
                    }

                    // if resolution has been set then ignore any other modifications
                    $url = 'http://ticket_tracker.local/index.php?action=viewTicket&id='.$ticketId;
                    header("Location: $url");
                    die();
                }
            }
            catch(Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        }
    }

    public function reopenTicket($ticketId) {
        echo "Controller reopenTicket ". $ticketId;

        try {
            $success = $this->ticketController->setUnresolved($ticketId);

            if($success) {
                try {
                    $unsetResolvedTime = $this->ticketController->unsetResolvedTime($ticketId);
                }
                catch(Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            }
        }
        catch(Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
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

    private function uploadFile($ticketId, $files) {
       echo "Controller uploadFile " . $files['file']['name'] . "<br/>";

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
                    $url = 'http://ticket_tracker.local/index.php?action=viewTicket&id='.$ticketId;
                    header("Location: $url");
                    die();
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

    // now handled by ApiController
    /*public function outputTicket($ticketId, $format) {
        //echo "outputTicket " . $ticketId . " in format " . $format;
        $ticket = $this->ticketController->getTicketById($ticketId);
        $comments = $this->ticketController->getTicketComments($ticketId);
        $attachments = $this->ticketController->getTicketAttachments($ticketId);
        include __DIR__ . '/../service.php';

        $url = 'http://ticket_tracker.local/index.php?action=viewTicket&id='.$ticketId;
        header("Location: $url");
        die();*/

        
        /*echo http_build_query($ticket) . "<br/>";

        $url = 'http://ticket_tracker.local/service.php';

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, 'ticket=$ticket&format='.$format);

        $r = curl_exec($curl);

        curl_close($curl);

        print_r($r);*/
   // }
}
