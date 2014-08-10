<?php

//require_once dirname(__FILE__) . '/../db_con.php';
class db {
    private $connection;
    private $selectdb;
    private $lastQuery;
    private $config;
    private static $_instance = null;
    
    static public function getInstance($config = NULL) {
        if(is_null(self::$_instance)) {
            self::$_instance = new self($config);
        }
        return self::$_instance;
    }

    private function __construct($config) {
        $this->config = $config;
    }
    function __destruct() {
        
    }

    public function openConnection() {
        try {
            $this->connection = mysqli_connect($this->config->hostname, $this->config->username, $this->config->password);
            $this->selectdb = mysqli_select_db($this->connection, $this->config->database);
            if (mysqli_connect_error()) {
                throw new mysqli_sql_exception(mysqli_connect_error(), mysqli_connect_errno());
            }
        } catch(mysqli_sql_exception $e) {
            return $e->getMessage();
        }
    }

    public function closeConnection() {
        try {
            mysqli_close($this->connection);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function query($query) {
        try {
            if(empty($this->connection)) {
                $this->openConnection();
                if (mysqli_connect_error()) {
                    $error = "ERROR:" . mysqli_connect_error() . " Error number:" . mysqli_connect_errno();
                    return $error;
                }
                $this->lastQuery = mysqli_query($this->connection, $this->sanitizeMySQL($query));
                if (mysqli_error($this->connection)) {
                    $error = "ERROR:" . mysqli_error($this->connection) . " Error number:" . mysqli_errno($this->connection);
                    return $error;
                } else {
                    return $this->lastQuery;
                }
                $this->closeConnection();
            } else {
                $this->lastQuery = mysqli_query($this->connection, $this->sanitizeMySQL($query));
                if (mysqli_error($this->connection)) {
                    $error = "ERROR:" . mysqli_error($this->connection) . " Error number:" . mysqli_errno($this->connection);
                    return $error;                    
                } else {
                    return $this->lastQuery;
                }
            }
        } catch (Exception $e) {
            return $e;
        }
    }
    
    public function lastQuery() {
        return $this->lastQuery;
    }
    
    public function pingServer() {
        try {
            if(!mysqli_ping($this->connection)) {
                return false;
            } else {
                return true;
            }
        } catch (Exception $e) {
            return $e;
        }
    }
    
    public function insert($table, $data) {
        try {
            $query = "INSERT INTO `$table` SET ";
            $i = 0;
            foreach ($data as $key => $value) {
                $i++;
                if ($i === count($data)) {
                    $query .= "`$key` = '$value'";
                } else {
                    $query .= "`$key` = '$value', ";
                }
            }
            return $this->query($query);
        } catch (Exception $e) {
            return $e;
        }
    }
    
    public function update($table, $id, $data) {
        try {
            $keySearch = key($id);
            $keyValue = implode($id);
            $query = "UPDATE `$table` SET ";
            $i = 0;
            foreach ($data as $key => $value) {
                $i++;
                if ($i === count($data)) {
                    $query .= "`$key` = '$value'";
                } else {
                    $query .= "`$key` = '$value', ";
                }
            }
            $query .= " WHERE `$keySearch` = '$keyValue'";
            return $this->query($query);
        } catch (Exception $e) {
            return $e;
        }
    }
    
    public function hasRows() {
        try {
            if(mysqli_num_rows($result)>0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return $e;
        }
    }
        
    public function countRows($result) {
        try {
            return mysqli_num_rows($result);
        } catch (Exception $e) {
            return $e;
        }            
    }
    
    public function affectedRows($result) {
        try {
            return mysqli_affected_rows($result);
        } catch (Exception $e) {
            return $e;
        }            
    }
    
    public function fetchAssoc($result) {
        try {
            return mysqli_fetch_assoc($result);
        } catch (Exception $e) {
            return $e;
        }
    }
    
    public function fetchArray($result) {
        try {
            return mysqli_fetch_array($result);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function fetchRow($result) {
        try {
            return mysqli_fetch_row($result);
        } catch (Exception $e) {
            return $e;
        }
    }
    
    public function getMysqlResult($result, $i = NULL) {
        try {
            $row = $this->fetchRow($result);
            if ($i) {
                return $row[$i];
            } else {
                return $row[0];
            }
        } catch (Exception $e) {
            return $e;
        }
    }
  
    public function toArray($result) {
        $results = array();
        try {
            while(($row = $result->fetch_assoc()) != false) {
                $results[] = $row;
            }
            return $results;
        } catch (Exception $e) {
            return $e;
        }
    }

    private function sanitizeString($var) {
        $var = stripslashes($var);
        $var = strip_tags($var);
        return $var;
    }

    public function sanitizeMySQL($var) {
        //$var = $this->sanitizeString($var);
        return $var;
    }
}
