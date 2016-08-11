<?php

class User {

	private $conn;

	function __construct() {

		require_once '../db/DB_Connect.php';
		$db = new Db_Connect();
		$this->conn = $db->connect();

	}

	public function isUserExisted($phoneNumber) {

		$sql = "SELECT * FROM `users` WHERE `phonenumber`='$phoneNumber'";

		$results = $this->conn->query($sql);

		if ($user = $results->fetch_assoc()) {

			return true;
		}

		return false;
	}

	public function registerUser() {

	}

	public function
}