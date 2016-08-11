<?php

class UserLevel {

	private $conn;

	function __construct() {

		require_once '../db/DB_Connect.php';
		$db = new Db_Connect();
		$this->conn = $db->connect();

	}

	public function getUserLevel($phoneNumber) {

		$level = 0;

		$sql = "SELECT `level` FROM `session_levels` WHERE `phoneNumber`='$phoneNumber'";

		$results = $this->conn->query($sql);

		if ($result = $results->fetch_assoc()) {

			$level = $result['level'];
		}

		return $level;
	}

}