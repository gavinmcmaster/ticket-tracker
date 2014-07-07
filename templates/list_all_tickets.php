<div>

    <h3>hello <?php echo Session::getInstance()->__get('user_name'); ?> </h3><br/>
    <?php
        if(count($allTickets) == 0) {
            echo "<h4>There are currently no tickets logged</h4><br/>";
        }
        else{ ?>
            <h4>There are <?php echo count($allTickets) ?> tickets.</h4>
            <!--<table class="table table-striped table-bordered table-condensed">-->
            <table class="table table-striped table-bordered table-condensed">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Summary</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Assignee</th>
                    <th>Created</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach($allTickets as $ticket) {
                        $date = date_create($ticket['created_time']);
                        $formattedDate = date_format($date, 'd/m/y');
                        $ticketId = $ticket['id'];
                        $ticketUrl = "http://localhost/training/web/ticket_tracker/index.php?action=viewTicket&id=".$ticketId;
                        $assignee = $this->userController->getUserById($ticket['assigned_to_id']);
                        $ticketType = $this->ticketController->getTicketTypeById($ticket['type_id']);
                        $status =  $this->ticketController->getStatusTypeById($ticket['status_type_id']);
                        $priority = $this->ticketController->getPriorityTypeById($ticket['priority_type_id']);
                        $priorityType = $priority['type'];

                        switch($priorityType) {
                            case "critical":
                                echo "<tr class='error'>";
                                break;
                            case "major":
                                  echo "<tr class='warning'>";
                                break;
                            default:
                                echo "<tr>";
                        }

                        echo "<td><a href=".$ticketUrl." title='View ticket'>".$ticketId."</a></td><td><a href=".$ticketUrl." title='View ticket'>".$ticket['title']."</a></td><td>".$ticketType['type']."</td><td>".$status['type']."</td><td>".$assignee['name']."</td><td>". $formattedDate. "</td></tr>";
                    }
                ?>
                </tbody>

            </table>


       <?php } ?>

</div>

