<form role="form" class="resolve_ticket" id='resolve' action="index.php?action=viewTicket&id=<?php echo $ticketId; ?>" method="post">
    <div class="pull-left">
        <h4>Resolve Ticket</h4>
        Resolve as:
        <select class="form-control"name="resolutionType">
            <option value=0></option>
            <?php
            foreach($resolutionTypes as $resolutionType) {
                echo "<option value=".$resolutionType['id'].">".$resolutionType['type']."</option>";
            }
            ?>
        </select>

        <input type="hidden" name="resolve" value="1" />

        <div>
            <?php
            echo "<button type='submit' class='btn btn-default'>Resolve Ticket</button>";
            ?>
        </div>
    </div>
</form>
