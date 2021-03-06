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
    
    /**
     * 
     * @param array $data
     * @param string $separator
     * @return string
     */
    
    private function dataAsQuery($data, $separator = ",") {
        $query = '';
        $i=0;
        foreach ($data as $key => $value) {
            $i++;
            if ($i === count($data)) {
                $query .= "`$key` = '$this->sanitizeString($value)'";
            } else {
                $query .= "`$key` = '$this->sanitizeString($value)' " . $separator . " ";
            }
        }
        return $query;
    }
    
    /**
     * 
     * @param string $var
     * @return string
     */
    
    private function sanitizeString($var) {
        $var = mysql_real_escape_string($var);
        return $var;
    }
    
    /**
     *
     * @return object
     * @throws mysqli_sql_exception
     */
    
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

    /**
     *
     * @return \Exception
     */
    
    public function closeConnection() {
        try {
            mysqli_close($this->connection);
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     *
     * @param string $query
     * @return string|\Exception
     */
    
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
    
    /**
     *
     * @param string $table
     * @param array $data
     * @return \Exception
     */
    public function insert($table, $data) {
        try {
            $query = "INSERT INTO `$table` SET ";
            $query .= $this->dataAsQuery($data);
            return $this->query($query);
        } catch (Exception $e) {
            return $e;
        }
    }
    
    /**
     *
     * @param string $table
     * @param array $condition
     * @param array $data
     * @return \Exception
     */
    public function update($table, $condition, $data) {
        try {
            $query = "UPDATE `$table` SET ";
            $keySearch = key($condition);
            $keyValue = $this->sanitizeString(implode($condition));
            $query .= $this->dataAsQuery($data);
            $query .= " WHERE `$keySearch` = '$keyValue'";
            return $this->query($query);
        } catch (Exception $e) {
            return $e;
        }
    }
    
    /**
     *
     * @param string $table
     * @param array $condition
     * @param array $logic
     * @return \Exception
     */
    
    public function delete($table, $condition, $logic = 'AND') {
        try {
            $query = "DELETE FROM `$table` WHERE ";
            $query .= $this->dataAsQuery($condition, $logic);
            return $this->query($query);
        } catch (Exception $e) {
            return $e;
        }
    }
    
    /**
     * 
     * @return \Exception|boolean
     */
    
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
    
    /**
     * 
     * @param object $result
     * @return \Exception
     */
    
    public function countRows($result) {
        try {
            return mysqli_num_rows($result);
        } catch (Exception $e) {
            return $e;
        }            
    }
    
    /**
     * 
     * @param object $result
     * @return \Exception
     */
    
    public function affectedRows($result) {
        try {
            return mysqli_affected_rows($result);
        } catch (Exception $e) {
            return $e;
        }            
    }
    
    /**
     * 
     * @param object $result
     * @return \Exception
     */
    
    public function fetchAssoc($result) {
        try {
            return mysqli_fetch_assoc($result);
        } catch (Exception $e) {
            return $e;
        }
    }
    
    /**
     * 
     * @param object $result
     * @return \Exception
     */
    
    public function fetchArray($result) {
        try {
            return mysqli_fetch_array($result);
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * 
     * @param object $result
     * @return \Exception
     */
    
    public function fetchRow($result) {
        try {
            return mysqli_fetch_row($result);
        } catch (Exception $e) {
            return $e;
        }
    }
    
    /**
     * 
     * @param object $result
     * @param int $i
     * @return \Exception
     */
    
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
    
    /**
     * 
     * @return object
     */
    
    public function lastQuery() {
        return $this->lastQuery;
    }
    
    /**
     * 
     * @return \Exception|boolean
     */
    
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
    
    /**
     * 
     * @param object $result
     * @return \Exception
     */
    
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
}