<div>
    <div class="row">
    <?php
    	$commentNum = 1;
        foreach($allCommentsData as $comment) {
            $userdata = $this->userController->getUserById($comment['added_by_id']);
            $createdTime = date_create($comment['created_time']);
            echo "<div class='span12'><div class='pull-right'><span id='comment:".$commentNum."'><a href='#comment:".$commentNum."''>comment".$commentNum."</a></span></div><div class='well'>" . $userdata['name']. " added a comment - " . date_format($createdTime,'d/M/Y H:i' ) . "<br/><br/>" .$comment['comment'] ."</div></div>";
        	$commentNum++;
        }
    ?>
     </div>
</div>


