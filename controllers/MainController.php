<?php

require_once 'interfaces/iMainController.php';

class MainController implements IMainController {

	protected $dbo;
    protected $db_host = "localhost";
    protected $db_name = "ticket_tracker"; 
    protected $db_user = "root";
    protected $db_pass = "gav1n";

    protected $userController = null;
    protected $ticketController = null;
    protected $config = null;

    public function __construct($configObject) {
        //echo "Controller constructor";
        $this->config = $configObject;
        $this->init();
    }

    protected function init() {
        //echo "Controller init";
        $this->dbo = new Database($this->db_host, $this->db_name, $this->db_user, $this->db_pass);

        if($this->dbo->getIsConnected()) {
            //echo "db is connected";
            if(is_null($this->userController)) $this->userController = new UserController($this->dbo, $this->config);
            if(is_null($this->ticketController)) $this->ticketController = new TicketController($this->dbo);
        }
    }

    public function handleAction($action) {}


}