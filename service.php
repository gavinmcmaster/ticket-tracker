<?php
	
	switch($format) {
		case 'csv':
			 $type = 'text/csv';
			 break;
		case 'json':
			$type = 'application/json';
			break;
		case 'xml':
			$type = 'text/xml';
			break;
		case 'plain':
			$type = 'text/plain';
			break;
		default:
			$type = 'text/plain';
	}

	$data = array();

	foreach ($ticket as $k => $v) {
		$data[$k] = $v; 
	}

	$data['comments'] = $comments;
	$data['attachments'] = $attachments;

	// set the content-type header
	header("Content-Type: $type");

	if ($type=='application/json') {
		$output = json_encode($data, JSON_PRETTY_PRINT);
			$fp = fopen('ticket_'. $ticket['id'] .'.json', 'w');
			fwrite($fp, $output);
			fclose($fp);
	} elseif ($type=='text/csv') {
		//outputCSV();
		/*$output = '';
		$fp = fopen('ticket_'. $ticket['id'] .'.csv', 'w');

		foreach ($data as $fields) {
		    fputcsv($fp, $fields);
		}

		fclose($fp);*/
	} elseif ($type=='text/plain') {
		$output = print_r($data, 1);
	} elseif ($type=='text/xml') {
		 //Creates XML string and XML document using the DOM 
	    $xml = new DomDocument('1.0', 'UTF-8'); 

	    $ticketNode = $xml->createElement("ticket");

	    foreach ($ticket as $k => $v) {
			$element = $xml->createElement( $k, $v );
			$ticketNode->appendChild($element);
		}

		$commentsNode = $xml->createElement("comments");
		foreach ($comments as $k => $v) {
			$element = $xml->createElement( $k, $v );
			//$commentsNode->appendChild($element);
		}
		$ticketNode->appendChild($commentsNode);

		$attachmentsNode = $xml->createElement("attachments");

		$ticketNode->appendChild($attachmentsNode);

		$xml->appendChild( $ticketNode );

	    
	    $xml->formatOutput = true;

	    // save XML as string or file 
	    $test1 = $xml->saveXML();
	   
	    //echo $test1;
	    $xml->save('ticket_'. $ticket['id'] . '.xml'); // save as file
	}

	function outputCSV() {
		$output = '';
		$fp = fopen('ticket_'. $ticket['id'] .'.csv', 'w');

		foreach ($data as $fields) {
		    fputcsv($fp, $fields);
		}

		fclose($fp);
	}

