<div>
    <div class="row">
    <?php
        foreach($allCommentsData as $comment) {
            $userdata = $this->userController->getUserById($comment['added_by_id']);
            $createdTime = date_create($comment['created_time']);
            echo "<div class='span12'><div class='well'>" . $userdata['name']. " added a comment - " . date_format($createdTime,'d/M/Y H:i' ) . "<br/><br/>" .$comment['comment'] ."</div></div>";
        }
    ?>
     </div>
</div>


