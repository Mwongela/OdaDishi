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

		$this->startApp();
	}

	function __destruct() {

		$this->printResults();
	}

	public function startApp() {

		$level = $this->userLevel->getUserLevel($this->sessionId);

		switch ($level) {
			case '0':
				$this->response = $this->getMainMenu();
				break;

			case '1':
				$this->routeLevel1();
				break;

			default:
				$this->response = $this->getMainMenu();
				break;
		}

	}

	public function printResults() {

		header("Content-type: text/plain");
		echo $this->response;
	}

	public function getMainMenu() {

		$menu = "";
		$menu .= "CON Welcome to Oda Dishi. Please select option \n";
		$menu .= "1. Order Food \n";
		$menu .= "2. Order Drink \n";
		$menu .= "3. Order Dessert\n";
		$menu .= "4. Seller\'s Portal";

		$this->userLevel->updateUserLevel($this->sessionId, $this->phoneNumber, 1);

		return $menu;
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
				$this->response = str_replace("Welcome to Oda Dishi", "Invalid choice", $this->getMainMenu());
				break;
		}

	}

	public function getSellerPortal() {
		$isRegistered = $this->user->isUserExisted($this->phoneNumber);

		if($isRegistered) {

		} else {
			// Register user
			// name, national Id, location, type = seller, PIN
			
		}
	}
}
