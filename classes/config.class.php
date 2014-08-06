<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Config
 *
 * @author Григорий
 */
class config {
    public $hostname;
    public $username;
    public $password;
    public $database;
    
    function __construct($hostname = NULL, $username = NULL, $password = NULL, $database = NULL) {
        $this->hostname = !empty($hostname) ? $hostname : "";
        $this->username = !empty($username) ? $username : "";
        $this->password = !empty($password) ? $password : "";
        $this->database = !empty($database) ? $database : "";
    }
    function __destruct() { 
    }
}