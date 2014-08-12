<?php
	foreach($allAttachmentsData as $attachment) {
		$addedByUserData = $this->userController->getUserById($attachment['added_by_id']);
        $addedTime = date_create($attachment['added_time']);
        $fileName = $filePath = $attachment['filepath'];
        $id = $attachment['id'];
        $dirStrLen = strlen(ATTACHMENTS_UPLOAD_DIRECTORY);

        //echo "attachment id " . $id . "<br/>";

       	if(strpos($filePath, ATTACHMENTS_UPLOAD_DIRECTORY) === false) {
        	echo "The " . gettype(ATTACHMENTS_UPLOAD_DIRECTORY) . " " . ATTACHMENTS_UPLOAD_DIRECTORY . " was not found in the " . gettype($filePath) ." '$filePath'<br/>";
        }
        else {
        	$fileName = substr($filePath, $dirStrLen);
        }

		echo "<a href='" . $filePath . "'>".$fileName . "</a><i> added by " . $addedByUserData['name'] . " on " . date_format($addedTime,'d/M/Y H:i' ) . "</i>";
        echo "   <a href='./index.php?action=downloadAttachment&download=true&id=" .$id. "&ticketId=" .$ticketId."'>[download]</a>";
		echo "<br/>";
	}
?>
