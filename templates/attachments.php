<!-- <div class="row"> -->
    <!-- <div class="pull-left"> -->
    <?php
		foreach($allAttachmentsData as $attachment) {
			$addedByUserData = $this->userController->getUserById($attachment['added_by_id']);
            $addedTime = date_create($attachment['added_time']);
            $fileName = $filePath = $attachment['filepath'];
            $dirStrLen = strlen(ATTACHMENTS_UPLOAD_DIRECTORY);

           	if(strpos($filePath, ATTACHMENTS_UPLOAD_DIRECTORY) === false) {
            	echo "The " . gettype(ATTACHMENTS_UPLOAD_DIRECTORY) . " " . ATTACHMENTS_UPLOAD_DIRECTORY . " was not found in the " . gettype($filePath) ." '$filePath'<br/>";
            }
            else {
            	$fileName = substr($filePath, $dirStrLen);
            }

			echo "<a href='" . $filePath . "'>".$fileName . "</a><i> added by " . $addedByUserData['name'] . " on " . date_format($addedTime,'d/M/Y H:i' ) . "</i><br/>";
		}
	?>
	<!--  -->
<!--  -->