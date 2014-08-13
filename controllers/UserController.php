<?php


class UserController {

	private $dbo = null;
	private $user = null;
    private $config = null;

	public function __construct($dbObject, $configObject) {
        $this->dbo = $dbObject;
        $this->config = $configObject;
        $this->init();
     }

     private function init() {
     	//echo 'UserController init';
     	if(is_null($this->user)) {
            $this->user = new User($this->dbo);
        }
     }

     public function login() {
        //echo "UserController:login " . $_POST['loginInputUserName'] . " - " . $_POST['loginInputPassword'];
         if(isset($_POST['loginInputUserName'])) $username = $_POST['loginInputUserName'];
         if(isset($_POST['loginInputPassword'])) $password = $_POST['loginInputPassword'];

         if(isset($username) && isset($password)) {
           return $this->user->verify($username, md5($this->config->salt.$password));
         }

         return false;
     }

     public function register() {
     	// no need to clean with htmlspecialchars, PDO takes care of security checking
        if(isset($_POST['registerInputName'])) $username = $_POST['registerInputName'];
        if(isset($_POST['registerInputEmail'])) $email = $_POST['registerInputEmail'];
        if(isset($_POST['registerInputPassword1'])) $password = $_POST['registerInputPassword1'];
        if(isset($_POST['userType'])) $userTypeID = $_POST['userType'];

        if(isset($userTypeID) && !is_int($userTypeID)) {
            $userTypeID = (int)$userTypeID;
        }
        //echo " " . $username . " - " .$email . ' - ' . $password . " - " . $userTypeID . " - " . is_int($userTypeID);

        if(isset($username) && isset($email) && isset($password) && isset($userTypeID) && is_int($userTypeID)) {
            //echo " - add " .$username . " to the db";
            //$password = md5($salt.$password);
            return $this->user->insert($username, $email, md5($this->config->salt.$password), $userTypeID);
        }

        return false;
     }

     public function getUserById($id) {
        return $this->user->getUserById($id);
     }

    public function getUserPermissionTypeById($id) {
        return $this->user->getUserPermissionTypeById($id);
    }

    public function getLastInsertId() {
        return $this->user->getLastInsertId();
     }

    public function fetchUserTypes() {
        return $this->user->fetchUserTypes();
    }

    public function fetchAllUsers() {
        return $this->user->fetchAllUsers();
    }
 }



