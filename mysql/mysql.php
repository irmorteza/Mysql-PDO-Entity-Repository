<?php
/**
 * BaseEntityRepository - This class make a entity wrapper for your class
 * Author: Morteza Iravani
 * version: 2.1
 * Create Date : 01 nov 2013
 * Last modify : 17 may 2015
 * site: https://github.com/irmorteza/Mysql-PDO-Entity-Repository
 *       https://ir.linkedin.com/in/mortezairavani

 mysql class v 2.1
 set to PDO tools
 uses prepare to avoid sql injection
 by irmorteza
 v 2 : 01 nov 2013
 v 2.1 : 17 may 2015
 How To:

		-- with parametrs
		$query = "call MNG_SignUp(:fname, :lname, :email, :password);";
		$params = array(':fname' => $fname, ':lname' => $lname, ':email' => $email, ':password' => $password);
		$res = mysql::sql_execute_return_table($query, $params);


		-- without parametrs
		$query = "select * from member;";
		$res = mysql::sql_execute_return_table($query);
 */

include_once 'enums.php';
function mysql_error_handle($e, $sql_query, $params = null){
	echo '<div style="display:none;">Error 2013 occured, please try later</div>';// . $e->getMessage();
	$f = fopen("mysql_error.log", "a"); 
	fwrite($f, "\n[".date("Y-m-d H:i:s")."] \nsql_query: ".$sql_query."\nparams: ".print_r($params, true)."\n".$e); 
	fclose($f);
	exit;
}
	
class mysql{
    public static function sql_execute($sql_query, $params = null){
		try {
			$pdo = new PDO("mysql:host=".Enums::HostName.";dbname=".Enums::DBName, Enums::UserName, Enums::Pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

			$stmt = $pdo->prepare($sql_query);
			$stmt->execute($params);
            return $stmt->rowCount(); // Row Affected
		} catch (PDOException $e) {
			mysql_error_handle($e, $sql_query, $params);
		}
	}

    public static function sql_execute_return_table_row($sql_query, $params = null){
		try {
			$pdo = new PDO("mysql:host=".Enums::HostName.";dbname=".Enums::DBName, Enums::UserName, Enums::Pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
			
			
			$stmt = $pdo->prepare($sql_query);
			$stmt->execute($params);
			$result = $stmt->fetchAll();
			if(count($result)>0)
				return $result[0];
			else
				return $result;		
		} catch (PDOException $e) {
			mysql_error_handle($e, $sql_query, $params);
		}
  	}        

	public static function sql_execute_return_table($sql_query, $params = null){
		try {		
			$pdo = new PDO("mysql:host=".Enums::HostName.";dbname=".Enums::DBName, Enums::UserName, Enums::Pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
					
			$stmt = $pdo->prepare($sql_query);
			$stmt->execute($params);
			$result = $stmt->fetchAll();
			return $result;	
		} catch (PDOException $e) {
			mysql_error_handle($e, $sql_query, $params);
		}

	}
	
	public static function sql_execute_nonequery($sql_query) {
		// this is depricated
		$con = mysqli_connect ( Enums::HostName, Enums::UserName, Enums::Pass, Enums::DBName );
		// Check connection
		if (mysqli_connect_errno ()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error ();
		}
	
		mysqli_query ( $con, $sql_query );
	
		mysqli_close ( $con );
	}
	
	public static function sql_execute_return_doubletable($sql_query,$sql_query2){
	$mysqli = new mysqli(Enums::HostName, Enums::UserName,Enums::Pass, Enums::DBName);
	$ret_array = array();
	$mysqli->query("SET NAMES 'utf8';");
	$ret_val = array();
	$result = $mysqli->query($sql_query);
	
	if($result){
	     // Cycle through results
	    while ($row = $result->fetch_array(MYSQL_BOTH)){
	        $ret_val[] = $row;
	    }
	    // Free result set
	    $result->close();
    	if($mysqli->more_results()) $mysqli->next_result();
	}

	$ret_array [] = $ret_val;

	$ret_val2 = array();
	$result2 = $mysqli->query($sql_query2);
	
	if($result2){
	     // Cycle through results
	    while ($row = $result2->fetch_array(MYSQL_BOTH)){
	        $ret_val2[] = $row;
	    }
	    // Free result set
	    $result2->close();
    	if($mysqli->more_results()) $mysqli->next_result();
	}
	$ret_array [] = $ret_val2;

	return ($ret_array);
	}

	public static function columns($table_name)
    {
        $sql_query = "SHOW COLUMNS FROM $table_name";
        try {
            $ar_columns = array();
            $res = self::sql_execute_return_table($sql_query);
            foreach ($res as $item) {
                $ar_columns[] = $item['Field'];
            }
            return $ar_columns;
        } catch (PDOException $e) {
            mysql_error_handle($e, $sql_query);
        }
    }

}


?>