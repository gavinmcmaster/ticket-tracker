<div>
    <div class="row">
    <?php
    	$commentNum = 1;
        foreach($allCommentsData as $comment) {
            $userdata = $this->userController->getUserById($comment['added_by_id']);
            $createdTime = date_create($comment['created_time']);
            echo "<div class='span12'><div class='pull-right'><span id='comment:".$commentNum."'><a href='#comment:".$commentNum."''>comment".$commentNum."</a></span></div><div class='well'>" . $userdata['name']. " added a comment - " . date_format($createdTime,'d/M/Y H:i' ) . "<br/><br/>" .$comment['comment'] ."</div>";
            echo "<form role='form' class='create_ticket' id='editcomment' action='index.php?action=viewTicket&id=". $ticketId ."#comment:". $commentNum . "' method='post'>";
            echo  "<input type='hidden' name='editComment' value=" .$commentNum. " />";
            echo "<div class='pull-right'><button type='submit' class='btn btn-default'>Edit</button></div>";
            echo "</form>";
            echo "</div>";

            $commentNum++;
        }
    ?>
     </div>
</div>


