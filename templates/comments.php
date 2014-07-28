<div>
    <div class="row">
    <?php
    	$commentNum = 1;
        foreach($allCommentsData as $comment) {
            $addedByUserData = $this->userController->getUserById($comment['added_by_id']);
            $createdTime = date_create($comment['created_time']);
            $editedTime = null;

            if(strtotime($comment['edited_time']) > 0) {
                $editedTime = date_create($comment['edited_time']);
                $editedByUserData = $this->userController->getUserById($comment['last_edited_by_id']);
            }

            if(isset($editCommentId) && is_int($editCommentId) && $editCommentId==$commentNum) {
                include __DIR__ . '/../templates/edit_comment_form.php';
            }
            else {
                echo "<div class='span12'><div class='pull-right'><span id='comment:".$commentNum."'><a href='#comment:".$commentNum."''>comment".$commentNum."</a></span></div><div class='well'>" . $addedByUserData['name']. " added a comment - " . date_format($createdTime,'d/M/Y H:i' );
                echo "<br/><br/>" .$comment['comment'];
                if(isset($editedTime)) {
                    $editedByUserName = isset($editedByUserData['name']) ? $editedByUserData['name'] : "unknown";
                    echo "<br/><br/>last edited by..." . $editedByUserName . " - " . date_format($editedTime,'d/M/Y H:i' );
                }

                echo "</div>";
                echo "<form role='form' class='create_ticket' id='editComment' action='index.php?action=viewTicket&id=". $ticketId ."#comment:". $commentNum . "' method='post'>";
                echo  "<input type='hidden' name='editComment' value=" .$commentNum. " />";
                echo "<div class='pull-right'><button type='submit' class='btn btn-default'>Edit</button></div>";
                echo "</form>";
                echo "</div>";
            }
            $commentNum++;
        }
    ?>
     </div>
</div>


