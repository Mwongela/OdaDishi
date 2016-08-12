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

	public function getFoodsForUser($phoneNumber) {

		$foods = array();

		$sql = "SELECT * FROM `food` WHERE `phoneNumber`='$phoneNumber'";

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

	public function startFoodSavingProcess($phoneNumber, $sessionId) {

	}

	public function saveFoodName($name, $foodId) {

		$sql = "UPDATE `food` SET `name`='$name' WHERE `id` = '$foodId'";

		$results = $this->conn->query($sql);

		return $results;
	}

	public function saveFoodPrice($price, $foodId) {

		$sql = "UPDATE `food` SET `price`=$price WHERE `id` = '$foodId'";

		$results = $this->conn->query($sql);

		return $results;
	}

	public function saveFoodType($type, $foodId) {

		$sql = "UPDATE `food` SET `type`='$type' WHERE `id` = '$foodId'";

		$results = $this->conn->query($sql);

		return $results;
	}

	public function activateFood() {

	}

}
