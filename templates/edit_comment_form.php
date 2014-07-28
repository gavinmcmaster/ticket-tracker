<div class="span12">
	<?php  echo "<span id='comment:".$commentNum."'><a href='#comment:".$commentNum."''>comment".$commentNum."</a></span>"; ?>
	<form role="form" class="view_ticket" id='editcomment' action="index.php?action=viewTicket&id=<?php echo $ticketId ?>#comment:<?php echo $commentNum ?>"  method="post">
	    <div class="form-group description">
	        <textarea class="field span8" id="textarea" rows="8" name="commentInput"><?php echo $comment['comment']; ?></textarea>
	    </div>
	    <div>
	    	<button type="submit" name="cancel_edit" class="btn btn-default">Cancel</button>
	        <button type="submit" name="submit_comment_edit" value=<?php echo $comment['id'] ?> class="btn btn-default">Submit changes</button>
	    </div>
	</form>
</div>