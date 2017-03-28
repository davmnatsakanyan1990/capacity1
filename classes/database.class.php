<?php
class database {

	var $_sql			= '';	
	/** @var Internal variable to hold the connector resource */
	var $_resource		= '';
	/** @var Internal variable to hold the query result*/
	var $_result        = ''; 
		/** @var Internal variable to hold the query result*/
	var $_insertId      = ''; 

	//$host = '';
	/**
	* Database object constructor
	* @param string Database host
	* @param string Database user name
	* @param string Database user password
	* @param string Database name
	* @param string Common prefix for all tables
	* @param boolean If true and there is an error, go offline
	*/
	function database() {
		global $glob;
		$host = $glob['dbhost'];
		$user = $glob['dbusername'];
		$pass = $glob['dbpassword'];
		$db = $glob['dbdatabase'];
		if ($this->_resource = @mysql_connect( $host, $user, $pass )) {
			mysql_select_db($db) or die('Cant select database'.mysql_error());
			//echo "in";
		}
		else{
			echo "Could not connect to the database!";
			exit;
		}				
	}
	
	/**
	* Execute the query
	* @return mixed A database resource if successful, FALSE if not.
	*/
	function query($sql) {
		$_sql = $sql;		
		return $_result = mysql_query($_sql);				
	}
	
	/**
	* Execute the query for insert
	* @return auto increment id
	*/
	function insert($table, $dbFields) {
	
		$field = array();
		$value = array();
		
		foreach ( $dbFields as $k => $v) {
		  	$v = addslashes(stripslashes($v));
				
			$field[] = $k;
			$value[] = $v;			
		}
		
		$f = implode('`,`',$field);
		$val = implode("','",$value);
		
		$insertSql = "INSERT INTO `$table` (`$f`) VALUES ('$val')";		
		//echo $insertSql;
		$result = mysql_query($insertSql);								
		$this->_insertId = mysql_insert_id();
			
		return $this->_insertId;
	}
	
	/**
	* Execute the query for update
	* @return true for success
	*/
	function update($table, $dbFields, $where) {

		$updateSql = "UPDATE $table SET ";
		$i=0;
		foreach ( $dbFields as $k => $v) {
			$v = addslashes(stripslashes($v));
			
			if ($i==0){
				$updateSql .= " $k = '$v' ";				
			}
			else{
				$updateSql .= ", $k = '$v' ";
			}			
			$i++;
		}
		
		 $updateSql .= " WHERE $where";
		//echo  $updateSql.'<br/>';
		 $result = mysql_query($updateSql);
		
		return true;
	}
	
	/**
	* Execute the query for sekect
	* @return array contains result
	*/
	function select($vars = "*", $table, $where = "", $orderBy = "", $groupBy = "", $resultType = MYSQL_ASSOC ){
		
		if ($vars != "*"){
			if (is_array($vars)){
				$vars = implode(",",$vars);
			}
		}				
				
		$selectSql = "SELECT ".$vars." FROM ".$table." WHERE 1 ".$where." ".$groupBy." ".$orderBy;
		
		//echo $selectSql;
		//exit;
		
		$resource = mysql_query($selectSql);
		$result = array();
		while($row = mysql_fetch_array($resource,$resultType)){
			$result[] = $row;
		}
		
		return $result;
	}
	
	/**
	* Execute the query for delete
	* @return true
	*/
	function delete($table, $where) {

		$deleteSql = "DELETE FROM $table WHERE $where ";
		//echo $deleteSql;
		//exit;
				
		$result = mysql_query($deleteSql);
		
		return true;
	}
	
	/**
	* Called for taking last insert id
	* @return last inserted id
	*/
	function getInsertId(){
		echo $this->_insertId;
	}
	
	/**
	* Execute the query for num of row count
	* @return number of rows for result
	*/
	function numRows($sql){
		$_sql = $sql;
		$_result = mysql_query($_sql);
		$results = mysql_num_rows($_result);
		mysql_free_result($_result);
		return $results;
	}
	
	/**
	* Clode db connection
	*/
	function dbClose(){
		mysql_close($this->_resource);
	}
	
	/**
	* fetch the mysql result resource
	* @return fetched array
	*/
	function fetchArray($rs){
        return @mysql_fetch_array($rs);
	}
	function  printData(){
		echo  'Get Database Data';
	}

}		
?>