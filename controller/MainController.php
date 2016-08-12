<?php

require_once "model/UserLevel.php";
require_once "model/User.php";

class MainController {

	private $sessionId;
	private $serviceCode;
	private $phoneNumber;
	private $text;
	private $userResponse;

	private $response = "";

	private $userLevel;
	private $user;

	function __construct($sessionId, $serviceCode, $phoneNumber, $text, $userResponse) {

		$this->sessionId = $sessionId;
		$this->serviceCode = $serviceCode;
		$this->phoneNumber = $phoneNumber;
		$this->text = $text;
		$this->userResponse = $userResponse;

		$this->userLevel = new UserLevel();
		$this->user = new User();

		$this->app();
	}

	public function app() {

		$level = $this->userLevel->getUserLevel($this->sessionId);

		// if($level !== 0 || $level !== 1) {
		//
		// 	if($this->userResponse == "") {
		//
		// 		$this->response = str_replace("CON", "CON Invalid input.", $this->response)
		//
		// 	}
		// }

		switch ($level) {
			case '0':
				$this->displayMainMenu();
				break;

			case '1':
				$this->routeLevel1();
				break;

			case '40':

				$user = $this->user->getUser($phoneNumber);
				$pin = $user['pin'];

				if(strcmp($pin, $this->userResponse) === 0) {

					$this->displaySellerMainMenu();
					return;

				} else {

					$this->response = "CON Wrong PIN. Enter PIN";

				}
				$this->printResults();
				break;

			case '411':
				$this->user->saveName($this->userResponse, $this->phoneNumber);
				$this->response = "CON Enter national ID no.";
				$this->userLevel->updateUserLevel($this->sessionId, $this->phoneNumber, 412);
				$this->printResults();
				break;

			case '412':
				$this->user->saveNationalId($this->userResponse, $this->phoneNumber);
				$this->response = "CON Enter your Location";
				$this->userLevel->updateUserLevel($this->sessionId, $this->phoneNumber, 413);
				$this->printResults();
				break;

			case '413':
				$this->user->saveLocation($this->userResponse, $this->phoneNumber);
				$this->response = "CON Enter your PIN";
				$this->userLevel->updateUserLevel($this->sessionId, $this->phoneNumber, 414);
				$this->printResults();
				break;

			case '414':
				$this->user->saveTemporaryPin($this->userResponse, $this->sessionId);
				$this->response = "CON Confirm your PIN";
				$this->userLevel->updateUserLevel($this->sessionId, $this->phoneNumber, 415);
				$this->printResults();
				break;

			case '415':
				if(strcmp($this->userResponse, $this->user->getTemporaryPin($this->sessionId)) === 0) {

					$this->user->savePin($this->userResponse, $this->phoneNumber);
					$this->user->activateAccount($this->phoneNumber);
					$this->response = "CON Registration Successful. Enter PIN to Login";
					$this->userLevel->updateUserLevel($this->sessionId, $this->phoneNumber, 40);

				} else {

					$this->response = "CON PIN does not match. Confirm your PIN";
				}

				$this->printResults();
				break;

			default:
				$this->displayMainMenu();
				break;
		}

	}

	public function printResults() {

		header("Content-type: text/plain");
		echo $this->response;
	}

	public function displayMainMenu($err = false) {

		$menu = "";
		$menu .= "CON Welcome to Oda Dishi. Please select option \n";
		$menu .= "1. Order Food \n";
		$menu .= "2. Order Drink \n";
		$menu .= "3. Order Dessert\n";
		$menu .= "4. Seller\'s Portal";

		$this->userLevel->updateUserLevel($this->sessionId, $this->phoneNumber, 1);

		if($err) {
			$menu = str_replace("Welcome to Oda Dishi", "Invalid choice", $menu);
		}

		$this->response = $menu;

		$this->printResults();
	}

	public function routeLevel1() {

		switch ($this->userResponse) {
			case '1':
				# code...
				break;

			case '2':
				break;

			case '3':
				break;

			case '4':
				$this->response = $this->getSellerPortal();
				break;

			default:
				$this->displayMainMenu(true);
				break;
		}

	}

	public function getSellerPortal() {
		$isRegistered = $this->user->isUserExisted($this->phoneNumber);

		if($isRegistered) {

			$this->response = "CON welcome back. Enter PIN to Login";
			$this->userLevel->updateUserLevel($this->sessionId, $this->phoneNumber, 40);

		} else {
			// Begin registration process
			// Register user
			// name, national Id, location, type = seller, PIN
			$this->userLevel->updateUserLevel($this->sessionId, $this->phoneNumber, 411);
			$this->user->savePhoneNumber($this->phoneNumber);
			$this->user->saveType('S', $this->phoneNumber);
			$this->response = "CON Enter your name";
			$this->printResults();
		}
	}

	public function displaySellerMainMenu($err = false) {

		$user = $this->user->getUser($this->phoneNumber);

		$menu = "CON Welcome " . $user['name'] . ". Select Option\n";
		$menu .= "1. Orders \n";
		$menu .= "2. Manage Foods \n";
		$menu .= "3. Account";

		if($err) {

			str_replace("Welcome " . $user['name'], "Invalid input", $menu);
		}

		$this->response = $menu;
	}
}
