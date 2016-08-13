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

		$foodSql = "INSERT INTO `food`(`phonenumber`) VALUES('$phoneNumber')";

		$results = $this->conn->query($foodSql);

		$id = $this->conn->insert_id;

		$sql = "UPDATE `session_levels` SET `foodId`= $id WHERE `session_id`='$sessionId'";

		return $this->conn->query($sql);
	}

	public function getFoodId($sessionId) {

		$sql = "SELECT `foodId` FROM `session_levels` WHERE `session_id` LIKE '%$sessionId%'";

		$result = $this->conn->query($sql);

		$session = $result->fetch_assoc();

		return $session['foodId'];
	}

	public function saveFoodName($name, $sessionId) {

		$foodId = $this->getFoodId($sessionId);

		$sql = "UPDATE `food` SET `name`='$name' WHERE `id` = '$foodId'";

		$results = $this->conn->query($sql);

		return $results;
	}

	public function saveFoodPrice($price, $sessionId) {

		$foodId = $this->getFoodId($sessionId);

		$sql = "UPDATE `food` SET `price`=$price WHERE `id` = '$foodId'";

		$results = $this->conn->query($sql);

		return $results;
	}

	public function saveFoodType($type, $sessionId) {

		$foodId = $this->getFoodId($sessionId);

		$sql = "UPDATE `food` SET `type`='$type' WHERE `id` = '$foodId'";

		$results = $this->conn->query($sql);

		return $results;
	}

	public function activateFood($sessionId) {

		$foodId = $this->getFoodId($sessionId);

		$sql = "UPDATE `food` SET `status`='ACTIVE' WHERE `id` = '$foodId'";

		$results = $this->conn->query($sql);

		return $results;
	}

}
