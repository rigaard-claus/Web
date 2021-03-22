<?php

namespace sqlQueryHelper;

/**
 * Description of SQLqueryHelper
 * Simple SQL framework
 * @author Rigaard
 */
class sqlQueryHelper {

    const type_VARCHAR = 0;
    const type_INT = 1;
    const type_FLOAT = 2;
    const type_BIT = 3;
    const SQL_SELECT = 'select';
    const SQL_INSERT = 'insert';
    const SQL_UPDATE = 'update';

    public function __construct() {
        $this->query = new __queryData(); // init query class
        $this->success = false;
    }

    public function __destruct() {
        if (isset($connecton)) {
            $connecton->close(); // close db connection
        }
    }

    ///////////////////////////////////
    ///set connection mysqli_connect///
    //////////////////////////Rigaard//
    ///////////////////////22.03.2021//
    public $connecton;
    //////////////////////////////
    ///select associative array///
    /////////////////////Rigaard//
    //////////////////22.03.2021//
    public $data = array();
    public $error;
    public $query;
    public $debug;
    public $operation;
    public $success;

    public function query2sql() {
        switch ($this->operation) {
            case self::SQL_SELECT :
                $this->select();
                break;
            case self::SQL_INSERT :
                $this->insert();
                break;
            case self::SQL_UPDATE :
                $this->update();
                break;
            default :
                $this->error = "sql_operation undefined";
                break;
        }
    }

    public function select() {
        try {
            if (!empty($this->query->values)) {
                $values = "";
                foreach ($this->query->values as $val) {
                    if ($values === "") {
                        $values = " " . $val;
                    } else {
                        $values = $values . "," . $val;
                    }
                }
                $selectQuery = self::SQL_SELECT . $values . " from " . $this->query->table;
            } else {
                $selectQuery = self::SQL_SELECT . " * from " . $this->query->table;
            }

            $result = $this->connecton->query($selectQuery);
            if ($result->num_rows > 0) {
                $numRow = 0;
                while ($row = $result->fetch_assoc()) {
                    $this->data[$numRow] = $row;
                    $numRow++;
                }
                $this->success = true;
            } else {
                echo "<br/>$selectQuery 0 results";
            }
        } catch (Exception $ex) {
            $this->error = $ex;
        }
    }

    public function insert() {
        try {
            $parameters = $this->query->values;
            if (!empty($parameters)) {
                $keys = array_keys($parameters);
                $values = "";
                $valuesData = "";
                foreach ($keys as $key) {
                    $formattedData = "";
                    switch ($parameters[$key]) {
                        case str_contains($parameters[$key], '(BIT)') :
                            $formattedData = str_replace("(BIT)", "", $parameters[$key]);
                            break;
                        case str_contains($parameters[$key], '(VARCHAR)') :
                            $formattedData = "'" . str_replace("(VARCHAR)", "", $parameters[$key]) . "'";
                            break;
                        case str_contains($parameters[$key], '(FLOAT)') :
                            $formattedData = str_replace("(FLOAT)", "", $parameters[$key]);
                            break;
                        case str_contains($parameters[$key], '(INT)') :
                            $formattedData = str_replace("(INT)", "", $parameters[$key]);
                            break;
                        default :
                            $formattedData = "'" . $parameters[$key] . "'";
                            break;
                    }
                    if ($values === "") {
                        $values = $key;
                        $valuesData = $formattedData;
                    } else {
                        $values = $values . "," . $key;
                        $valuesData = $valuesData . "," . $formattedData;
                    }
                }
                $insertQuery = self::SQL_INSERT . " into " . $this->query->table . "(" . $values . ") values (" . $valuesData . ")";
                $this->connecton->query($insertQuery);
                $this->success = true;
            } else {
                $this->success = false;
            }
        } catch (Exception $ex) {
            $this->error = $ex;
        }
    }

    public function update($queryData) {
        try {
            
        } catch (Exception $ex) {
            $this->error = $ex;
        }
    }

}

/////////////////
///query class///
////////Rigaard//
/////22.03.2021//
class __queryData {

    public function __construct() {
        $this->values = array(); // new array
    }

    public $table; // tablename
    public $values = array();
    public $attribute; //desc limit and other...

}

?>