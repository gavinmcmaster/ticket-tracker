<form role="form" class="modify_ticket" id='modify' action="index.php?action=modifyTicket&id=<?php echo $ticketId ?>#comment:<?php echo $commentNum ?>"  method="post">

	<div class="modify_ticket">
		<div class="row">
			<h4>Modify ticket</h4>
			
	        <div class="pull-left">
	            <h5>Actions</h5>
	            Resolve as: <br/>
	            Reassign to: <br/>
	            Accept: <br/>
	        </div>

	        <div class="pull-right">
	            <h5>Change properties</h5>
	            Type:   booyah <br/>
	            Priority: foo <br/>
	        </div>

		</div>
		<div>
		    <button type="submit" class="btn btn-default">Submit changes</button>
		</div>
	</div>
</form>
