<?php

class User {

	private $conn;

	function __construct() {

		require_once 'db/DB_Connect.php';
		$db = new Db_Connect();
		$this->conn = $db->connect();

	}

	public function isUserExisted($phoneNumber) {

		$user = $this->getUser($phoneNumber);

		if($user && $user['pin'] != NULL &&
			$user['phonenumber'] != NULL &&
			$user['nationalID'] != NULL &&
			$user['location'] != NULL &&
			strcmp($user['status'], "ACTIVE") == 0) {

			return true;
		}

		return false;
	}

	public function savePhoneNumber($phoneNumber) {

		$sql = "INSERT INTO `users`(`phonenumber`) VALUES('$phoneNumber')";

		$results = $this->conn->query($sql);

		return $results;

	}

	public function saveName($name, $phoneNumber) {

		$sql = "UPDATE `users` SET `name`='$name' WHERE `phonenumber` = '$phoneNumber'";

		$results = $this->conn->query($sql);

		return $results;
	}

	public function saveNationalId($nationalID, $phoneNumber) {

		$sql = "UPDATE `users` SET `nationalID`='$nationalID' WHERE `phonenumber` = '$phoneNumber'";

		$results = $this->conn->query($sql);

		return $results;
	}

	public function saveType($type, $phoneNumber) {

		$sql = "UPDATE `users` SET `type`='$type' WHERE `phonenumber` = '$phoneNumber'";

		$results = $this->conn->query($sql);

		return $results;
	}

	public function saveLocation($location, $phoneNumber) {

		$sql = "UPDATE `users` SET `location`='$location' WHERE `phonenumber` = '$phoneNumber'";

		$results = $this->conn->query($sql);

		return $results;
	}

	public function saveTemporaryPin($pin, $sessionId) {

		$sql = "UPDATE `session_levels` SET `temp_pin`= $pin WHERE `session_id`='$sessionId'";

		$results = $this->conn->query($sql);

		return $results;
	}

	public function getTemporaryPin($sessionId) {

		$sql = "SELECT `temp_pin` FROM session_levels WHERE `session_id` = '" . $sessionId . "' ";

		$query = $this->conn->query($sql);

		$tempPin = 0;

		if ($result = $query->fetch_assoc()) {

			$tempPin = $result['temp_pin'];
		}

		return $tempPin;
	}

	public function savePin($pin, $phoneNumber) {

		$sql = "UPDATE `users` SET `pin`='$pin' WHERE `phonenumber` = '$phoneNumber'";

		$results = $this->conn->query($sql);

		return $results;
	}

	public function activateAccount($phoneNumber) {

		$sql = "UPDATE `users` SET `status`='ACTIVE' WHERE `phonenumber` = '$phoneNumber'";

		$results = $this->conn->query($sql);

		return $results;
	}

	public function getUser($phoneNumber) {

		$sql = "SELECT * FROM `users` WHERE `phonenumber` LIKE '%$phoneNumber%'";

		$results = $this->conn->query($sql);

		$user = $results->fetch_assoc();

		return $user;
	}
}
