<?php
class Mysql {
	
	
	private $host = "localhost"; 
	private $user = "root";
	private $pass = "";
	private $name = "family_sum";
	private $test; 
	
	
	//Constructor
	function Mysql($test=false) {
		mysql_connect($this->host, $this->user, $this->pass);
		mysql_select_db($this->name);
		$this->test = $test;
	}
	
	private function error() {
		echo "mysql.handler> Syntax Error";
	}

	public function createTable($name, $fields) {
		$sql = "CREATE TABLE ".$name." (".$fields.");";			
		mysql_query($sql);
	}
	
	public function deleteTable($table) {
		$sql = "DROP TABLE IF EXISTS ".$table.";";
		mysql_query($sql);
	}
	
	public function cleanTable($table) {	
		$sql = "TRUNCATE TABLE ".$table.";";
		mysql_query($sql);
	}
	
	public function insert($table, $fields, $values) {	
		$sql = "INSERT INTO ".$table." (";
		$sql .= $fields;
		$sql .= ") VALUES (";
		$sql .= $values;
		$sql .= ");";
		if ($this->test){
			echo '<p>'.$sql.'</p>';
		}
		mysql_query($sql);
	}
	
	public function delete($table, $clause) {
		$sql = "DELETE FROM ".$table." ".$clause.";";
		if ($this->test){
			echo '<p>'.$sql.'</p>';
		}
		mysql_query($sql);
	}
	
	public function update($table, $set, $clause) {	
		$sql = "UPDATE ".$table." SET ";
		$sql .= $set;
		$sql .= " ".$clause.";";	
		if ($this->test){
			echo '<p>'.$sql.'</p>';
		}
		mysql_query($sql);
	}
	
	public function select($table, $select, $clause) {
		$sql = "SELECT ".$select." FROM ".$table." ".$clause.";";
		if ($this->test){
			echo '<p>'.$sql.'</p>';
		}
		$query = mysql_query($sql);
		return $query;
	}
	
	public function fetchAssoc($query) {
		$row = mysql_fetch_assoc($query);
		//mysql_free_result($sql);
		return $row;
	}
	
	public function numRows($table, $select, $clause) {
		$sql = $this->select($table, $select, $clause);
		$row = mysql_num_rows($sql);
		//mysql_free_result($sql);
		return $row;
	}
	
	public function query($return, $query) {
		if($return == 1) {
			$query = mysql_query($query);
			return $query;
		} else if($return == 0) {
			mysql_query($query);
		}
	}
	
	public function close() {
		mysql_close();
	}
	
	public function __destruct(){
		
	}
}

?>
