<div class="navbar">
    <?php
    $session = Session::getInstance();

    if($session->__isset('user_name')) { ?>
        <a href="http://ticket_tracker.local/index.php?action=logout"><button class="btn logout">Logout</button></a>
        <a href="http://ticket_tracker.local/index.php?action=listTickets"><button class="btn list_tickets">List Tickets</button></a>
        <?php
             $userPermissionTypeId = Session::getInstance()->__get('permission_type_id');
             if($userPermissionTypeId != USER_PERMISSION_VIEW && $userPermissionTypeId != USER_PERMISSION_UPDATE){
                echo "<a href='http://ticket_tracker.local/index.php?action=createTicket'><button class='btn create_ticket'>Create Ticket</button></a>";
             }
       } else { ?>

        <h1>Login</h1>

        <form role="form" class="login" action="index.php?action=login" method="post">
            <div class="form-group name">
                <label for="loginInputUserName">User name</label>
                <input type="text" class="form-control" name="loginInputUserName" placeholder="Enter user name">
            </div>
            <div class="form-group password">
                <label for="loginInputPassword">Password</label>
                <input type="password" class="form-control" name="loginInputPassword" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-default">Login</button>
        </form>

        <h1>Register</h1>

        <form role="form" class="register" action="index.php?action=register" method="post">
            <div class="form-group name">
                <label for="registerInputName">Name</label>
                <input type="text" class="form-control" name="registerInputName" placeholder="Enter name">
            </div>
            <div class="form-group email">
                <label for="registerInputEmail">Email address</label>
                <input type="email" class="form-control" name="registerInputEmail" placeholder="Enter email">
            </div>
            <label for="registerUserType">User Type</label>


            <select class="form-control"name="userType">
             <?php
                $userTypes = $controller->getUserController()->fetchUserTypes();
                foreach($userTypes as $userType) {
                    echo "<option value=".$userType['id'].">".$userType['type']."</option>";
                }
             ?>
            </select>

            <div class="form-group password1">
                <label for="registerInputPassword1">Password</label>
                <input type="password" class="form-control" name="registerInputPassword1" placeholder="Password">
            </div>
            <!--<div class="form-group password2">
                <label for="registerInputPassword2">Confirm Password</label>
                <input type="password" class="form-control" id="registerInputPassword2" placeholder="Confirm Password">
            </div>-->
            <button type="submit" class="btn btn-default">Register</button>
        </form>

    <?php } ?>

</div>