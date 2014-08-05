<?php include('ui/header.htm') ?>
<?php

    require_once 'config.inc.php';
    require_once 'constants.php';
    require_once 'models/Session.php';
    $session = Session::getInstance();

    require_once 'models/Database.php';
    require_once 'models/User.php';
    require_once 'models/Ticket.php';
    require_once 'controllers/ControllerFactory.php';
    require_once 'controllers/UserController.php';
    require_once 'controllers/TicketController.php';
    
    $api = (isset($_GET['api'])) ? $_GET['api']: false;

    try {
        $controller = ControllerFactory::getController($api, $config);//new Controller($config);
    } catch(Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }

    if(!$api) {
        include_once 'templates/nav.php';
    }

    // user actions
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    if(method_exists($controller,$action)) $controller->handleAction($action);
?>

<?php include('ui/footer.htm') ?>
