<?php

namespace sqlQueryHelper;

/**
 * Description of SQLqueryHelper
 * Simple SQL framework
 * @author Rigaard
 */
define("VARCHAR", "0");
define("INT", "1");
define("FLOAT", "2");
define("BIT", "2");

define("SELECT", "select");
define("INSERT", "insert");
define("UPDATE", "update");
define("DELETE", "delete");

class sqlQueryHelper {

    public function __construct() {
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
    public $table;
    public $value = array();
    public $where; //condition (todo)
    public $attributes; //limit, order etc
    public $debug;
    public $success;

    ////////////////////////
    ///get set main value///
    ///////////////Rigaard//
    ////////////24.03.2021//
    public function addvalue($name, $type = null, $value = null) {
        $newValue = new __fieldType();
        $newValue->name = $name;
        $newValue->type = $type;
        $newValue->value = $value;
        array_push($this->value, $newValue);
    }

    /////////////////////////
    ///start query request///
    ////////////////Rigaard//
    /////////////24.03.2021//
    public function query2sql($operation) {
        switch ($operation) {
            case SELECT :
                $this->select();
                break;
            case INSERT :
                $this->insert();
                break;
            case UPDATE :
                $this->update();
                break;
            case DELETE :
                $this->delete();
                break;
            default :
                $this->error = "sql_operation undefined";
                break;
        }
    }

    public function select() {
        try {
            if (!empty($this->value)) {
                $values = "";
                foreach ($this->value as $val) {
                    if ($values === "") {
                        $values = " " . $val->name;
                    } else {
                        $values = $values . "," . $val->name;
                    }
                }
                $selectQuery = SELECT . $values . " from " . $this->table;
            } else {
                $selectQuery = SELECT . " * from " . $this->table;
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
            if (!empty($this->value)) {
                $values = "";
                $fields = "";
                foreach ($this->value as $field) {
                    if ($values === "") {
                        $values = $field->name;
                        $fields = $this->format($field);
                    } else {
                        $values = $values . "," . $field->name;
                        $fields = $fields . "," . $this->format($field);
                    }
                }
                $insertQuery = INSERT . " into " . $this->table . "(" . $values . ") values (" . $fields . ")";
                $this->connecton->query($insertQuery);
                $this->success = true;
            } else {
                $this->success = false;
            }
        } catch (Exception $ex) {
            $this->error = $ex;
        }
    }

    public function update() {
        try {
            if (!empty($this->where) and!empty($this->value)) {
                $values = "";
                foreach ($this->value as $field) {
                    if ($values === "") {
                        $values = $field->name . "=" . $this->format($field);
                    } else {
                        $values = $values . "," . $field->name . "=" . $this->format($field);
                    }
                }
                $updateQuery = UPDATE . " " . $this->table . " set " . $values . " where " . $this->where;
                echo "</br>";
                echo $updateQuery;
                echo "</br>";
                $this->connecton->query($updateQuery);
                $this->success = true;
            } else {
                $this->success = false;
            }
        } catch (Exception $ex) {
            $this->error = $ex;
        }
    }

    public function delete() {
        try {
            $this->success = false;
            if (empty($this->attributes)) {
                $deleteQuery = DELETE . " from " . $this->table;
                $this->connecton->query($deleteQuery);
            } else {
                echo "</br>TODO attr";
            }
            $this->success = true;
        } catch (Exception $ex) {
            $this->error = $ex;
        }
    }
    
    ////////////////////////////////////////
    ///convert values to string sql query///
    ///////////////////////////////Rigaard//
    ////////////////////////////24.03.2021//
    function format($field) {
        $formattedField = "";
        switch ($field->type) {
            case BIT:
                if ($field->value === false) {
                    $formattedField = "false";
                } else if ($field->value === true) {
                    $formattedField = "true";
                } else {
                    $formattedField = $field->value; // 0 or 1
                }
                break;
            case VARCHAR:
                $formattedField = "'" . $field->value . "'";
                break;
            case FLOAT:
                $formattedField = $field->value;
                break;
            case INT:
                $formattedField = $field->value;
                break;
            default :
                $formattedField = "'" . $field->value . "'";
                break;
        }
        return $formattedField;
    }
}

///////////////////
///field options///
//////////Rigaard//
///////24.03.2021//
class __fieldType {
    public $name;
    public $type;
    public $value;
}

?>