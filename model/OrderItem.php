<?php

class OrderItem {

	private $conn;

	function __construct() {

		require_once 'db/DB_Connect.php';
		$db = new Db_Connect();
		$this->conn = $db->connect();

	}

	public function addOrderItem($orderId, $foodId, $quantity = 1) {

		$sql = "INSERT INTO `OdaDishi`.`orderItem` (`id`, `order`, `food`, `quantity`) VALUES (NULL, $orderId, $foodId, '1')";

		$results = $this->conn->query($sql);

		return $results;
	}
}
