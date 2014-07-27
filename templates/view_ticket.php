<div class="view_ticket">
    <div>
        <h3><?php echo "#". $ticketId. " ". $ticket['title']?>
        <?php
            if($ticketIsResolved) {
                echo " <i>(resolved)</i>";
            }
        ?>
        </h3>
    </div>

    <div>
        <?php
            if($ticketIsResolved) {

                $output = "<form role='form' method='post' action='index.php?action=viewTicket&id=".$ticketId ."'>";
                $output .= "<button type='submit' class='btn btn-default'>Reopen issue</button>";
                $output .=  "<input type='hidden' name='reopen' value='1' />";
                $output .= "</form>";

                echo $output;
            }
        ?>
    </div>

    <div class="row">
        <div class="pull-left">
            <h4>Details</h4>
            Type:   <?php echo $ticketType ?> <br/>
            Priority: <?php echo $priorityType ?> <br/>
            <?php
                if($ticketIsResolved) {
                    echo "Resolved as: ".$resolvedAs."<br/>";
                }
            ?>
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

            Created: <?php echo date_format($timeCreated,'d/M/Y H:i' ) ?> <br/>
            Updated: <?php if(!empty($timeUpdated)) echo date_format($timeUpdated, 'd/M/Y H:i') ?> <br/>
            Resolved: <?php if(!empty($timeResolved)) echo date_format($timeResolved, 'd/M/Y H:i') ?> <br/>
        </div>
     </div>
    <div class="row">
        <div class="pull-left"> 
            <h4>Comments</h4>

            <?php
                if(count($allCommentsData) > 0) {
                    include __DIR__ . '/../templates/comments.php';
                }

                if($createComment && $userPermissionTypeId != USER_PERMISSION_VIEW) {
                    include __DIR__ . '/../templates/comment_form.php';
                }
                else if($userPermissionTypeId != USER_PERMISSION_VIEW && !$ticketIsResolved) {
                   echo "<div><a href="."'http://ticket_tracker.local/index.php?action=viewTicket&id=".$ticketId."&addComment=true#newcomment'"."><button class="."'btn comment'".">Add comment</button></a></div>";
                }

                if($userPermissionTypeId != USER_PERMISSION_VIEW && !$ticketIsResolved) {
                    include __DIR__ . '/../templates/modify_ticket.php'; 
                    include __DIR__ . '/../templates/resolve_ticket.php';
                }
            ?>

            </div>
        </div>
    </div>
</div>