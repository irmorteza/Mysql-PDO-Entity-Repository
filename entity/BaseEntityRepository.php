<?php
/**

 * BaseEntityRepository - This class make a entity wrapper for your class
 * Author: Morteza Iravani
 * version: 1.1
 * Date: 2015-05-19
 * site: https://github.com/irmorteza/Mysql-PDO-Entity-Repository
 *       https://ir.linkedin.com/in/mortezairavani
 */

include_once 'mysql/mysql.php';
class BaseEntityRepository {
    private $__table;
    private $__columns;

    function __construct()
    {
        $this->__table = strtolower(get_class($this));
        $this->__columns = $this->GetColumns();
    }

    private function GetProperties($existsInTable=false)
    {
        try {
            $reflection = new ReflectionClass($this);
            $vars = array_keys($reflection->getdefaultProperties());
//        var_dump($reflection->getdefaultProperties());
            $reflection = new ReflectionClass(__CLASS__);
            $parent_vars = array_keys($reflection->getdefaultProperties());

            $my_child_vars = array();
            foreach ($vars as $key) {
//          if (!in_array($key, $parent_vars) && $key[0] != '_') {
                $flag = ($existsInTable ? in_array($key, $this->__columns) : true);
                if (!in_array($key, $parent_vars) && $flag && $key[0] != '_') {
//                echo $key;
                    $my_child_vars[] = $key;
                }
            }
            return $my_child_vars;
        } catch (Exception $e) {
            echo 'Error On GetProperties ' . $this->__table;
//            echo 'Error On inserting ' . $e->getMessage();
            return null;
        }
    }
    private function GetColumns(){
        return mysql::columns($this->__table);
    }


    function Load($search_key, $search_value)
    {
        try {
            $ar_key = $this->GetProperties(true);

            $query = "select *  from $this->__table where $search_key = :$search_key; ";
            $params = array(
                ":$search_key" => $search_value
            );
//            echo $query;
            $res = mysql::sql_execute_return_table_row($query, $params);
            if (count($res) > 0) {
                // set all prorerties to object
                foreach ($ar_key as $key) {
                    $this->$key = $res[$key];
                }

                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo 'Error On Loading ' . $this->__table;
//            echo 'Error On inserting ' . $e->getMessage();
            return false;
        }
    }
    /**
     * @return bool ;
     */
    function Save()
    {
        try {
            $ar_key = array();
            $ar_value = array();
            $ar_bind = array();
            $params = array();

            foreach ($this->GetProperties(true) as $key ) {
                if($key == 'id') continue;
                $ar_key[] = $key;
                $ar_bind[] = ':' . $key;
                $params[':' . $key] = $this->$key;
            }
//            var_dump($ar_key);
//            var_dump($ar_bind);
//            var_dump($ar_value);
//            var_dump($params);
            $keys_as_string = implode(', ', $ar_key);
            $binds_as_string = implode(', ', $ar_bind);
            $query = "INSERT INTO $this->__table ($keys_as_string)
                        VALUES ( $binds_as_string)";
//            echo $query;

            $rows_affected = mysql::sql_execute($query, $params);
            if ($rows_affected)
                return true;
            else
                return false;
        } catch (Exception $e) {
            echo 'Error On inserting ' . $this->__table;
//            echo 'Error On inserting ' . $e->getMessage();
            return false;
        }

    }
    function Update()
    {
        try {
            $ar_set = array();
            $params = array();
            $id = null;

            foreach ($this->GetProperties(true) as $key ) {
                if($key == 'id') {$id = $this->$key; continue;}
                $ar_set[] = $key . ' = :' . $key;
                $params[':' . $key] = $this->$key;
            }
            $params[':id'] = $id;

//            var_dump($ar_set);
//            var_dump($params);
            $set_as_string = implode(', ', $ar_set);
            $query = "UPDATE $this->__table set $set_as_string
                        where id=:id";
//            echo $query;
            $rows_affected = mysql::sql_execute($query, $params);
            if ($rows_affected)
                return true;
            else
                return false;
        } catch (Exception $e) {
            echo 'Error On Updating '. $this->__table;
//            echo 'Error On inserting ' . $e->getMessage();
            return false;
        }

    }
    function Delete()
    {
        try {
            $id = null;
            foreach ($this->GetProperties(true) as $key ) {
                if ($key == 'id') {
                    $id = $this->$key;
                    break;
                }
            }
            if($id == null)
                throw new Exception('It can\'t delete user by id of null ');
            $query = "delete from $this->__table where id = :id;";
            $params = array(
                ':id' => $id
            );
//            var_dump($params);
//            echo $query;
            $rows_affected = mysql::sql_execute($query, $params);
            if ($rows_affected)
                return true;
            else
                return false;
        } catch (Exception $e) {
            echo 'Error On Deleting '. $this->__table . PHP_EOL. ' #  message:' . $e->getMessage();
//            echo 'Error On inserting ' . $e->getMessage();
            return false;
        }
    }

    function Count($search_key, $search_value)
    {
        try {
            $query = "select count(1) cnt from $this->__table where $search_key = :$search_key; ";
            $params = array(
                ":$search_key" => $search_value
            );
//            echo $query;
            $res = mysql::sql_execute_return_table_row($query, $params);
            if (count($res) > 0) {
                return $res['cnt'];
            } else {
                return 0;
            }
        } catch (Exception $e) {
            echo 'Error On Count ' . $this->__table;
//            echo 'Error On inserting ' . $e->getMessage();
            return 0;
        }
    }
    function Exist($search_key, $search_value)
    {
        try {
            $cnt = $this->Count($search_key, $search_value);
//            echo '#'.$cnt.'@' ;
            if($cnt > 0)
                return true;
            else
                return false;
        } catch (Exception $e) {
            echo 'Error On Exist ' . $this->__table;
//            echo 'Error On inserting ' . $e->getMessage();
            return false;
        }
    }

    /**
     * @param $search_key
     * @param $search_value
     * @return number if id found else return null
     */
    function GetId($search_key, $search_value){
        $query = "select id from $this->__table where Username = :Username; ";
        $params = array(
            ":$search_key"  => $search_value
        );
//        echo $query;
        $res = mysql::sql_execute_return_table_row($query, $params);
        if (count($res) > 0)
            return $res['id'];
        else
            return null;
    }
}