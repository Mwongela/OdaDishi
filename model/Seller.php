<?php

require_once "User.php";

class Seller extends User {

	private $conn;

	function __construct() {

		require_once 'db/DB_Connect.php';
		$db = new Db_Connect();
		$this->conn = $db->connect();

	}

	// food management (CRUD)
	public function addDish() {
	}

	public function updateDish() {

	}

	public function getAvailableDishes() {

	}

	public function getDish() {

	}

	public function removeDish() {

	}

	// notification (SEND)
	public function sendNotification() {

	}

	// Orders
	public function getOrders() {

	}

	public function getTodayOrders() {

	}

}
