<?php
/**
 * Created by PhpStorm.
 * User: gavin
 * Date: 13/06/14
 * Time: 16:07
 */

class User {

    private $dbo;

    public function __construct($dbObject) {
        $this->dbo = $dbObject;
    }

    public function insert($name, $email, $password, $userTypeID) {
        $this->dbo->query("INSERT INTO users (name, email, password, user_type_id) VALUES (:name, :email, :password, :user_type_id)");
        $this->dbo->bind(':name', $name);
        $this->dbo->bind(':email', $email);
        $this->dbo->bind(':password', $password);
        $this->dbo->bind(':user_type_id', $userTypeID);
        $success = $this->dbo->execute();

        return $success;
        //echo " insert success?: ". $success . " last insert id?:" . $this->dbo->lastInsertId();
    }

    public function verify($name, $password) {
        //echo "User:verify " .$name ." - ".$password ."<br/>";
        $this->dbo->query("SELECT * FROM users WHERE name = :name AND password = :password");
        $this->dbo->bind(':name', $name);
        $this->dbo->bind(':password', $password);
        $this->dbo->execute();
        $result = $this->dbo->fetch();

        return $result;
    }

    public function getLastInsertId() {
        return $this->dbo->lastInsertId();
    }

    public function getUserById($id) {
        $this->dbo->query("SELECT * FROM users WHERE id = :id");
        $this->dbo->bind(':id', $id);
        $this->dbo->execute();
        $result = $this->dbo->fetch();

        return $result;
    }

    public function getUserPermissionTypeById($id) {
        $this->dbo->query("SELECT * FROM user_permission_types WHERE id = :id");
        $this->dbo->bind(':id', $id);
        $this->dbo->execute();
        $result = $this->dbo->fetch();

        return $result;
    }

    public function fetchUserTypes() {
        $this->dbo->query("SELECT * FROM user_types");
        $this->dbo->execute();
        $result = $this->dbo->fetchAll();

        return $result;
    }

    public function fetchAllUsers() {
        $this->dbo->query("SELECT * FROM users");
        $this->dbo->execute();
        $result = $this->dbo->fetchAll();

        return $result;
    }

} 