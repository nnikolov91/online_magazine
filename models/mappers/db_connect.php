<?php
/**
 * 
 * @author 
 *
 */
class DBConnect {
	protected $_database;
	function connect() {
		try {
			$this->_database = new PDO ( DB_DSN, DB_USERNAME, DB_PASSWORD );
			$this->_database->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( PDOException $e ) {
			echo $e->getMessage ();
		}
	}
	function disconnect() {
		$this->_database = null;
	}
	function query($sql) {
		try {
			$stmt = $this->_database->prepare ( $sql );
			$stmt->execute ();
			return $stmt;
		} catch ( PDOException $e ) {
			echo $e->getMessage ();
		}
	}
	function selectAllFrom($table) {
		return $this->query ( "select * from `" . $table . "`" );
	}
	function selectAllFromWhere($table, $where_clause) {
		return $this->query ( "select * from `" . $table . "` where " . $where_clause );
	}
	function selectById($table, $id, $id_column = "id") {
		return $this->query ( "select * from `" . $table . "` where `" . $id_column . "` = " . $id );
	}
	function _buildInsertQuery($array, $table) {
		$sql = "insert into " . $table . " (";
		foreach ( array_keys ( $array ) as $column ) {
			$sql = $sql . " " . $column . ",";
		}
		$sql = rtrim ( $sql, "," ) . ") values (";
		foreach ( array_values ( $array ) as $value ) {
			if ($value == "NOW()") {
				$sql = $sql . $value . ",";
			} else {
				$sql = $sql . "'" . $value . "',";
			}
		}
		return rtrim ( $sql, "," ) . ")";
	}
	function lastInsertId() {
		return $this->_database->lastInsertId ();
	}
	function responseIsEmpty($response) {
		if ($response->rowCount () > 0) {
			return false;
		}
		return true;
	}
}