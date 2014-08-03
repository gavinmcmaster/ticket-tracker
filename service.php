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

	// set the content-type header
	header("Content-Type: $type");
