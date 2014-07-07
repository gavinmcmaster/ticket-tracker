<?php include('ui/header.htm') ?>
<?php

    //$id = 0;

    require_once 'config.inc.php';
    // create session...every time ffs
    require_once 'models/Session.php';
    $session = Session::getInstance();

    //echo "username set in session:". $session->__isset('username');

    if($session->__isset('username')) {
        echo("Hello " . $session->__get('username'));
    }

    require_once 'models/Database.php';
    require_once 'models/User.php';
    require_once 'models/Ticket.php';
    require_once 'controllers/Controller.php';
    require_once 'controllers/UserController.php';
    require_once 'controllers/TicketController.php';

    try {
        $controller = new Controller($config);
    } catch(Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }

    include_once 'templates/nav.php';

    // user actions
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    if(method_exists($controller,$action)) $controller->$action();
?>

<?php include('ui/footer.htm') ?>
