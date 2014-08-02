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
            Assignee:   <?php echo $assignee ?> <br/>
            Reporter: <?php echo $reporter ?> <br/>
        </div>
    </div>
    <div class="row">
        <div class="pull-left">
            <h4>Description</h4>
            <?php echo $ticket['description']; ?>
        </div>
        <div class="pull-right">
            <h4>Dates</h4>

            Created: <?php echo date_format($dateCreated,'d/M/Y H:i' ) ?> <br/>
            Updated: <br/>
            Resolved: <?php if(!empty($dateResolved)) echo date_format($dateResolved, 'd/M/Y H:i') ?> <br/>
        </div>
     </div>
     <!-- <div class="row">
       
    </div> -->
    <div class="row">
        <h4>Attachments</h4>
        <?php
        if(count($allAttachmentsData) > 0) {
            include __DIR__ . '/../templates/attachments.php';
        }
        
        if($userPermissionTypeId != USER_PERMISSION_VIEW) {
            include __DIR__ . '/../templates/form_upload.php';
        }
        ?>
    </div>
    <div class="row">
        <div class="pull-left"> 
            <h4>Comments</h4>

            <?php
                define('USER_PERMISSION_VIEW', 1);

                if(count($allCommentsData) > 0) {
                    include __DIR__ . '/../templates/comments.php';
                }

                if($createComment && $userPermissionType != "view") {
                    include __DIR__ . '/../templates/comment_form.php';
                }
                else if($userPermissionType != "view") {
                   echo "<div><a href="."'http://ticket_tracker.local/index.php?action=viewTicket&id=".$ticketId."&addComment=true'"."><button class="."'btn comment'".">Add comment</button></a></div>";
                }
            ?>

            </div>
        </div>
    </div>
</div>