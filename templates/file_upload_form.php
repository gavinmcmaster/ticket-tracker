<!-- The data encoding type, enctype, MUST be specified as below -->
<div class='well'>
	<h5>Add attachment</h5>
	<form enctype="multipart/form-data" action="index.php?action=uploadFile&id=<?php echo $ticketId; ?>" method="post">
	    <!-- MAX_FILE_SIZE must precede the file input field -->
	    <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
	    <!-- Name of input element determines name in $_FILES array -->
	    Browse for file: <input name="file" type="file" />
	    <input type="submit" value="Upload File" />
	</form>
</div>