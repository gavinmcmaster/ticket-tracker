<?php
/**
 * Created by PhpStorm.
 * User: gavin
 * Date: 13/06/14
 * Time: 10:32
 */

//namespace models;

class Database {

    private $dbh;
    private $isConnected;
    private $stmt;

    public function __construct($host, $db, $user, $pass) {
        $this->host = $host;
        $this->db = $db;
        $this->user = $user;
        $this->pass = $pass;
        $this->connect();
    }

    private function connect() {
        try {
            //echo "connect to host " . $this->host . " db " . $this->db . " user " . $this->user . " pass " . $this->pass;
            $this->dbh = new PDO("mysql:host=".$this->host.";dbname=".$this->db,$this->user,$this->pass);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->isConnected = true;
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
            $this->isConnected = false;
            //die();
        }
    }

    public function getIsConnected() {
        return $this->isConnected;
    }

    public function query($query){
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null){
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute(){
        return $this->stmt->execute();
    }

    public function lastInsertId(){
        return $this->dbh->lastInsertId();
    }

    public function fetch() {
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll() {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} 