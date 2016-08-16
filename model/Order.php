<?php

class Order {

	private $conn;

	function __construct() {

		require_once 'db/DB_Connect.php';
		$db = new Db_Connect();
		$this->conn = $db->connect();

	}

	public function getOrderLocations() {

		$locations = array();

		$sql = "SELECT * FROM `location`";

		$results = $this->conn->query($sql);

		while ($row = mysqli_fetch_assoc($results)) {

			array_push($locations, array(
				'id'=> $row['id'],
				'location'=> $row['location']
			));
		}

		return $locations;
	}

	public function getLocation($locationId) {

		$sql = "SELECT * FROM `location` WHERE `id`=$locationId";

		$results = $this->conn->query($sql);

		$location = $results->fetch_assoc();

		if($location && $location['id'] != NULL && $location['location'] != NULL) {
			return $location;
		}

		return $false;
	}

	public function getBuyer($phoneNumber) {

		$sql = "SELECT * FROM `buyer` WHERE `phoneNumber`='$phoneNumber'";

		$results = $this->conn->query($sql);

		$buyer = $results->fetch_assoc();

		return $buyer;
	}

	public function hasUserOrderedBefore($phoneNumber) {

		$buyer = $this->getBuyer($phoneNumber);

		if($buyer && $buyer['phoneNumber'] != NULL && $buyer['location'] != NULL) {

			return true;
		}

		return false;
	}

	public function getFoodByType($type) {

		$foods = array();

		$sql = "SELECT * FROM `food` WHERE `type`='$type'";

		$results = $this->conn->query($sql);

		while($row = $results->fetch_assoc()) {

			array_push($foods, $row);
		}

		return $foods;
	}

	public function addBuyerLocation($phoneNumber, $locationId) {

		if($this->hasUserOrderedBefore($phoneNumber)) {
			return;
		}

		$sql = "INSERT INTO `OdaDishi`.`buyer` (`phoneNumber`, `locationId`) VALUES ('$phoneNumber', $locationId)";

		$results = $this->conn->query($sql);

		return $results;
	}

	public function startOrderProcess() {



	}
}
