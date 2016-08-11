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

	public function addUserLevel($sessionId, $phoneNumber, $level) {

		$sql = "INSERT INTO `OdaDishi`.`session_levels` (`session_id`, `phoneNumber`, `level`, `temp_pin`) VALUES ('$session_id', '$phoneNumber', '$level', '');"

		$results = $this->conn->query($sql);

		return $results;

	}

	public function updateUserLevel($sessionId, $phoneNumber, $level) {

		if($this->getUserLevel($phoneNumber) == 0) {

			$this->addUserLevel($sessionId, $phoneNumber, $level);

			return;
		}

		$level = "UPDATE `session_levels` SET `level`= $level WHERE `session_id`='$sessionId'";

		$results = $this->conn->($level);

		return $results;

	}

}