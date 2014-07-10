<div>
<form role="form" class="create_ticket" action="index.php?action=viewTicket&id=<?php echo $ticketId ?>"  method="post">
    <div class="form-group description">
        <textarea class="field span8" id="textarea" rows="8" name="commentInput" placeholder="Enter comment and submit..."></textarea>
    </div>
    <div>
        <button type="submit" class="btn btn-default">Submit Comment</button>
    </div>
</form>
</div>
