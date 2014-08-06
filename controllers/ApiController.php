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
                $content = $this->getTicketInJSONFormat($ticketId);
                break;
            case 'xml':
                $type = 'application/xml';
                $content = $this->getTicketInXMLFormat($ticketId);
                break;
            case 'plain':
                $type = 'text/plain';
                $content = $this->getTicketInPlainTextFormat($ticketId);
                break;
            case 'csv':
                if($this->config->outPutCSVToFile) {
                    header( 'Content-Type: text/csv' );
                    header( 'Content-Disposition: attachment;filename='.$filename);
                }
                $out = fopen('php://output', 'w');
                fputcsv($out, $this->getTicketInCSVFormat($ticketId));
                fclose($out);
                return;
            default:
                echo "error, the format specified is not valid";
        }

        $sendResponse = !($format=='csv' && $this->config->outPutCSVToFile);

        if($sendResponse) {
            $this->sendResponse($content, $type);
        }
    }

    private function sendResponse($content, $type) {
        //echo "sendResponse " .$type;
        header("Content-Type: $type");
        echo $content;
    }

    private function getTicketInJSONFormat($ticketId) {
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

    private function getTicketInXMLFormat($ticketId) {
        $ticket = $this->ticketController->getTicketById($ticketId);
        $comments = $this->ticketController->getTicketComments($ticketId);
        $attachments = $this->ticketController->getTicketAttachments($ticketId);

        $xml = new DomDocument('1.0', 'UTF-8'); 

        $ticketNode = $xml->createElement("ticket");
        foreach ($ticket as $k => $v) {
            $element = $xml->createElement( $k, $v );
            $ticketNode->appendChild($element);
        }

        $commentsNode = $xml->createElement("comments");
        foreach ($comments as $k => $v) {
            $commentNode = $xml->createElement("comment");

            foreach($v as $key => $value) {
                $element = $xml->createElement( $key, $value );
                $commentNode->appendChild($element);
            }

            $commentsNode->appendChild($commentNode);
        }
        $ticketNode->appendChild($commentsNode);

        $attachmentsNode = $xml->createElement("attachments");
        foreach ($attachments as $k => $v) {
            $attachmentNode = $xml->createElement("attachment");

            foreach($v as $key => $value) {
                $element = $xml->createElement( $key, $value );
                $attachmentNode->appendChild($element);
            }

            $attachmentsNode->appendChild($attachmentNode);
        }
        $ticketNode->appendChild($attachmentsNode);

        $xml->appendChild( $ticketNode );

        echo $xml->saveXML();
    }

    private function getTicketInPlainTextFormat($ticketId) {
        $ticket = $this->ticketController->getTicketById($ticketId);
        $comments = $this->ticketController->getTicketComments($ticketId);
        $attachments = $this->ticketController->getTicketAttachments($ticketId);
        $output = "";

        foreach ($ticket as $k => $v) {
            $output .= $k . ": " . $v . "\n";
        }

        $output .= "\n";

        $output .= "Comments\n";
        if(count($comments) >0) {
           $output .= "\n";
           foreach($comments as $comment) {
               foreach($comment as $k => $v) {
                    $output .= $k . ": " . $v . "\n";
                }
            }
        } else {
            $output .= "there are no comments\n";
        }

        $output .= "\n";

        $output .= "Attachments\n";
        if(count($attachments) >0) {
            $output .= "\n";
            foreach($attachments as $attachment) {
                foreach($attachment as $k => $v) {
                    $output .= $k . ": " . $v . "\n";
                }
                $output .= "\n";
            }
        } else {
            $output .= "there are no attachments\n";
        }

        return $output;
    }

    private function getTicketInCSVFormat($ticketId) {
        $ticket = $this->ticketController->getTicketById($ticketId);
        $comments = $this->ticketController->getTicketComments($ticketId);
        $attachments = $this->ticketController->getTicketAttachments($ticketId);
        $allTicket = array();

        foreach($ticket as $k=>$v) {
           //echo $k . ": " . $v . "\n";

          array_push($allTicket, $k, $v);
        }

        foreach($comments as $comment) {
            //echo "comments\n";
            array_push($allTicket, "comments");
            foreach($comment as $k => $v) {
                //echo "comment\n";
                array_push($allTicket, "comment");
                //echo $k . ": " . $v . "\n";
                array_push($allTicket, $k, $v);
            }
        }

        foreach($attachments as $attachment) {
            //echo "attachments\n";
            array_push($allTicket, "attachments");
            foreach($attachment as $k => $v) {
                //echo "attachment\n";
                array_push($allTicket, "attachment");
                //echo $k . ": " . $v . "\n";
                array_push($allTicket, $k, $v);
            }
        }

        return $allTicket;
    }
}