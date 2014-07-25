<span id="modify">
    <form role="form" class="modify_ticket" id='modify' action="index.php?action=viewTicket&id=<?php echo $ticketId; ?>" method="post">

        <div class="modify_ticket">
            <div class="row">
                <div class="span12">
                    <h4>Modify ticket</h4>

                    <div class="pull-left">
                        <h5>Actions</h5>
                        Resolve as:
                        <select class="form-control"name="resolutionType">
                            <option value=0></option>
                        <?php
                            foreach($resolutionTypes as $resolutionType) {
                                echo "<option value=".$resolutionType['id'].">".$resolutionType['type']."</option>";
                            }
                        ?>
                        </select>

                        <br/>
                        Reassign to:
                        <select class="form-control"name="assignTo">
                        <?php
                        $assigneeId = $assigneeData['id'];
                        if(!isset($assigneeId)) echo "<option value=0></option>";
                        echo "assignee is " .$assigneeId['id']. " - " . $assigneeData['name'] . "<br/>";
                        foreach($allUsers as $user) {
                            $output = (isset($assigneeId) && $assigneeId == $user['id'])
                                ?  "<option value='".$user['id']."' selected>".$user['name']."</option>"
                                : "<option value=".$user['id'].">".$user['name']."</option>";
                            echo $output;
                        }
                        ?>
                        </select>
                        <br/>
                    </div>

                    <div class="pull-right">
                        <h5>Change properties</h5>
                        Type:
                        <select class="form-control"name="ticketType">
                            <?php
                            $ticketTypeId = $ticket['type_id'];
                            foreach($ticketTypes as $type) {
                                $output = ($ticketTypeId == $type['id'])
                                    ? "<option value='".$type['id']."' selected>".$type['type']."</option>"
                                    : "<option value=".$type['id'].">".$type['type']."</option>";
                                echo $output;
                            }
                            ?>
                        </select>
                        <br/>
                        Priority:
                        <select class="form-control"name="priorityType">
                            <?php
                            $priorityTypeId = $ticket['priority_type_id'];
                            foreach($priorityTypes as $type) {
                                $output = ($priorityTypeId == $type['id'])
                                    ? "<option value='".$type['id']."' selected>".$type['type']."</option>"
                                    : "<option value=".$type['id'].">".$type['type']."</option>";

                                echo $output;
                            }
                            ?>
                        </select>
                        <br/>
                    </div>

                </div>

                <input type="hidden" name="modify" value="1" />

                <div>
                    <button type="submit" class="btn btn-default">Submit changes</button>
                </div>
            </div>
        </div>
    </form>
</span>
