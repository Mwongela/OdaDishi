<?php

class OrderItem {

	private $conn;

	function __construct() {

		require_once '../db/DB_Connect.php';
		$db = new Db_Connect();
		$this->conn = $db->connect();

	}

}