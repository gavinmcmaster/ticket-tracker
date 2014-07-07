<div class="view_ticket">
    <div>
        <h3><?php echo "#". $ticketId. " ". $ticket['title'] ?></h3>
    </div>
    <div class="row">
        <div class="pull-left">
            <h4>Details</h4>
            Type:   <?php echo $ticketType ?> <br/>
            Priority: <?php echo $priorityType ?> <br/>

        </div>
        <div class="pull-right">
            <h4>People</h4>

        </div>
    </div>
    <div class="row">
        <div class="pull-left">
            <h4>Description</h4>
        </div>
        <div class="pull-right">
            <h4>Dates</h4>

        </div>
     </div>
    <div class="row">
        <div class="pull-left">
            <h4>Comments</h4>

        </div>
    </div>
</div>