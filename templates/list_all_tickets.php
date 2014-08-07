<div>

    <h3>hello <?php echo Session::getInstance()->__get('user_name'); ?> </h3><br/>
    <?php
        if(count($allTickets) == 0) {
            echo "<h4>There are currently no tickets logged</h4><br/>";
        }
        else{
            $pageUrl = BASE_URL ."?action=listTickets";
            $sortBy = isset($_GET['sort']) ? $_GET['sort'] : null;
            $order = isset($_GET['order']) ? $_GET['order'] : 'asc';
            $idOrder = ($sortBy=='id') ? ($order=='asc') ? 'desc' : 'asc'  : 'asc';
            $summaryOrder = ($sortBy=='summary') ? ($order=='asc') ? 'desc' : 'asc'  : 'asc';
            $typeOrder = ($sortBy=='type') ? ($order=='asc') ? 'desc' : 'asc'  : 'asc';
            $statusOrder = ($sortBy=='status') ? ($order=='asc') ? 'desc' : 'asc'  : 'asc';
            $assigneeOrder = ($sortBy=='assignee') ? ($order=='asc') ? 'desc' : 'asc'  : 'asc';
            $createdOrder = ($sortBy=='created') ? ($order=='asc') ? 'desc' : 'asc'  : 'asc';

            if($sortBy) {
                //echo "sort tickets by: " .$sortBy . " " . $order . "</br>";
                $sort = ($order=='asc') ? SORT_ASC : SORT_DESC;
                switch($sortBy) {
                    case 'id':
                        $allTickets = $this->ticketController->sortTicketsOrder($allTickets, 'id', $sort);
                        break;
                    case 'summary':
                        $allTickets = $this->ticketController->sortTicketsOrder($allTickets, 'title', $sort);
                        break;
                    case 'type':
                        $allTickets = $this->ticketController->sortTicketsOrder($allTickets, 'type_id', $sort);
                        break;
                    case 'status':
                        $allTickets = $this->ticketController->sortTicketsOrder($allTickets, 'status_type_id', $sort);
                        break;
                    case 'assignee':
                        $allTickets = $this->ticketController->sortTicketsOrder($allTickets, 'assigned_to_id', $sort);
                        break;
                    case 'created':
                        $allTickets = $this->ticketController->sortTicketsOrder($allTickets, 'created_time', $sort);
                        break;
                    default:
                        echo "Invalid sort value " .$sortBy . " specified";
                }
            }

            ?>
            <h4>There are <?php echo count($allTickets) ?> tickets.</h4>
            <table class="table table-striped table-bordered table-condensed">
                <thead>
                <tr>
                    <th><a href="<?php echo $pageUrl; ?>&sort=id&order=<?php echo $idOrder; ?>">#</a> </th>
                    <th><a href="<?php echo $pageUrl; ?>&sort=summary&order=<?php echo $summaryOrder; ?>">Summary</a></th>
                    <th><a href="<?php echo $pageUrl; ?>&sort=type&order=<?php echo $typeOrder; ?>">Type</a></th>
                    <th><a href="<?php echo $pageUrl; ?>&sort=status&order=<?php echo $statusOrder; ?>">Status</a></th>
                    <th><a href="<?php echo $pageUrl; ?>&sort=assignee&order=<?php echo $assigneeOrder; ?>">Assignee</a></th>
                    <th><a href="<?php echo $pageUrl; ?>&sort=created&order=<?php echo $createdOrder; ?>">Created</a></th>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach($allTickets as $ticket) {
                        $date = date_create($ticket['created_time']);
                        $formattedDate = date_format($date, 'd/m/y');
                        $ticketId = $ticket['id'];
                        $ticketUrl = BASE_URL."?action=viewTicket&id=".$ticketId;
                        $assignee = $this->userController->getUserById($ticket['assigned_to_id']);
                        $ticketType = $this->ticketController->getTicketTypeById($ticket['type_id']);
                        $status =  $this->ticketController->getStatusTypeById($ticket['status_type_id']);
                        $priority = $this->ticketController->getPriorityTypeById($ticket['priority_type_id']);
                        $priorityType = $priority['type'];
                        $isResolved = isset($ticket['resolution_type_id']);

                        if($isResolved) {
                            echo "<tr class='resolved'>";
                        }
                        else {
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
                        }

                        echo "<td><a href=".$ticketUrl." title='View ticket'>".$ticketId."</a></td><td><a href=".$ticketUrl." title='View ticket'>".$ticket['title']."</a></td><td>".$ticketType['type']."</td><td>".$status['type']."</td><td>".$assignee['name']."</td><td>". $formattedDate. "</td></tr>";
                    }
                ?>
                </tbody>

            </table>


       <?php } ?>

</div>

