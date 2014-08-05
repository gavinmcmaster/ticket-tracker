<?php

class ApiController extends MainController {

    public function handleAction($action) {
    	
    	switch($action) {
            case "outputTicket":
                $ticketId = $_GET['id'];
                $format = $_POST['format'];
                $this->outputTicket($ticketId, $format);
                break;
            default:
                echo "error, the action specified is not valid";
        }
    }

    private function outputTicket($ticketId, $format) {
        //echo "ApiController, outputTicket " . $ticketId . " - " .$format;
       
        switch($format) {
            case 'json':
                $type = 'application/json';
                $content = $this->getTicketInJsonFormat($ticketId);
                break;
            /*default: 
                echo "error, the format specified is not valid";*/
        }

        $this->sendResponse($content, $type);
    }

    private function sendResponse($content, $type) {
        //echo "sendResponse " .$type;
        header("Content-Type: $type");
        echo $content;
    }

    private function getTicketInJsonFormat($ticketId) {
        $ticket = $this->ticketController->getTicketById($ticketId);
        $comments = $this->ticketController->getTicketComments($ticketId);
        $attachments = $this->ticketController->getTicketAttachments($ticketId);
        $data = array();

        foreach ($ticket as $k => $v) {
            $data[$k] = $v; 
        }

        $data['comments'] = $comments;
        $data['attachments'] = $attachments;

        return json_encode($data, JSON_PRETTY_PRINT);
    }

}