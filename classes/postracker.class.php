<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of postracker
 *
 * NOT FINISHED
 */
class postracker {
    
    private $time;
    private $systemID;
    private $ownerID;
    private $typeID;
    private $rfType;
    private $authorID;
    private $db;
    
    private $columns = array(
        "time",
        "systemID",
        "ownerID",
        "typeID",
        "rfType",
        "authorID"
    );
    
    public function __construct() {
        $this->authorID = $_SESSION['userID'];
        $this->db = db::getInstance();
    }
    
    private function makeInsQuery($data) {
        $i = 0;
        foreach ($data as $key => $value) {
            $i++;
            if ($i === count($data)) {
                $query .= "`$key` = '$value'";
            } else {
                $query .= "`$key` = '$value', ";
            }
        }
        return $query;
    }
    public function addToDB($id = NULL, $data) {
        $table = "postracker";
        $keys = array_keys($data);
        $diff = array_diff($this->columns, $keys);
        if (count($diff) === 0 AND !($id===NULL)) {
            $key = array(
                'id' => $id
            );
            return $this->db->update($table, $key, $data);
        } elseif(count($diff) === 0 AND count($data) === count($this->columns) AND $id === NULL) {
            return $this->db->insert($table, $data);
        } else {
            exit('Wrong data / no ID');
        }
    }
    public function getFromDB() {
        $query = "SELECT * FROM `postracker`";
        $result = $this->db->query($query);
        return $result;
    }
}