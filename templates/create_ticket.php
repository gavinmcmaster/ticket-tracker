<div class="create_ticket">
    <h4>Create New Ticket</h4>
    <form role="form" class="create_ticket" action="index.php?action=createTicket" method="post">
        <div class="form-group title">
            <label for="createTicketInputTitle">Summary</label>
            <input type="text" class="form-control" name="createTicketInputTitle">
        </div>
        <!-- <div class="form-group reporter">
            <label for="createTicketInputReporter">Reporter</label>
            <input type="text" class="form-control" name="createTicketInputReporter" placeholder=<?php echo Session::getInstance()->__get('user_name'); ?>>
        </div> -->
        <div class="form-group description">
            <label for="createTicketInputDescription">Description</label>
            <!--<input type="text" class="form-control input-description" name="createTicketInputDescription">-->
            <textarea class="field span8" id="textarea" rows="8" name="createTicketInputDescription" placeholder="A description must be entered..."></textarea>
        </div>

        <label for="createTicketType">Type</label>
        <select class="form-control"name="createTicketType">
        <?php
            foreach($ticketTypes as $ticketType) {
                echo "<option value=".$ticketType['id'].">".$ticketType['type']."</option>";
            }
        ?>
        </select>

        <label for="createPriorityType">Priority</label>
        <select class="form-control"name="createPriorityType">
         <?php
            foreach($ticketPriorityTypes as $priorityType) {
                echo "<option value=".$priorityType['id'].">".$priorityType['type']."</option>";
            }
         ?>
         </select>

        <label for="createAssignUser">Assign to:</label>
        <select class="form-control"name="createAssignUser">
            <?php
            echo "<option value=" ."'null'"."> </option>";
            foreach($allUsers as $user) {
                echo "<option value=".$user['id'].">".$user['name']."</option>";
            }
            ?>
        </select>

        <input type="hidden" name="create" value="1" />

        <div>
            <button type="submit" class="btn btn-default">Create Ticket</button>
        </div>
    </form>



</div>