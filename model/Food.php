<?php

class Food {

	private $conn;

	function __construct() {

		require_once 'db/DB_Connect.php';
		$db = new Db_Connect();
		$this->conn = $db->connect();

	}

	public function getFoods($type) {

		$foods = array();

		$sql = "SELECT * FROM `food` WHERE `type`=$type";

		$results = $this->conn->query($sql);

		while ($row = mysqli_fetch_assoc($results)) {

			$food = array(
				"id" => $row["id"],
				"name" => $row["name"],
				"price" => $row["price"]
				);

			array_push($foods, $food);
		}

		return $foods;
	}

}
