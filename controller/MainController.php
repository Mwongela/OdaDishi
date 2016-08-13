<?php

require_once "model/UserLevel.php";
require_once "model/User.php";
require_once "model/Food.php";

class MainController {

	private $sessionId;
	private $serviceCode;
	private $phoneNumber;
	private $text;
	private $userResponse;

	private $response = "";

	private $userLevel;
	private $user;
	private $food;

	function __construct($sessionId, $serviceCode, $phoneNumber, $text, $userResponse) {

		$this->sessionId = $sessionId;
		$this->serviceCode = $serviceCode;
		$this->phoneNumber = $phoneNumber;
		$this->text = $text;
		$this->userResponse = $userResponse;

		$this->userLevel = new UserLevel();
		$this->user = new User();
		$this->food = new Food();

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
				$this->routeMainHome();
				break;

			case '40':

				$user = $this->user->getUser($this->phoneNumber);
				$pin = $user['pin'];

				if(strcmp($pin, $this->userResponse) === 0) {

					$this->displaySellerMainMenu();
					return;

				} else {

					$this->response = "CON Wrong PIN. Enter PIN";

				}
				$this->printResults();
				break;

			case '420':
				$this->routeSellerHome();
				break;

			case '4220':
				$this->food->saveFoodName($this->userResponse, $this->sessionId);
				$this->response = "CON Enter price";
				$this->userLevel->updateUserLevel($this->sessionId, $this->phoneNumber, 4221);
				$this->printResults();
				break;

			case '4221':
				$this->food->saveFoodPrice($this->userResponse, $this->sessionId);
				$this->displayFoodType();
				break;

			case '4222':
				$this->routeSelectFoodType();
				break;

			case '4223':
				$this->routeAddNewFood();
				break;

			case '426':
				$this->routeManageFood();
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
					$this->response = "END Registration Successful.";
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

	public function routeMainHome() {

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
		}

		$this->printResults();
	}

	public function displaySellerMainMenu($err = false) {

		$user = $this->user->getUser($this->phoneNumber);

		$menu = "CON Welcome " . $user['name'] . ". Select Option\n";
		$menu .= "1. Orders \n";
		$menu .= "2. Manage Foods \n";
		$menu .= "3. Account";

		if($err) {
			$menu = str_replace("Welcome " . $user['name'], "Invalid input", $menu);
		}

		$this->userLevel->updateUserLevel($this->sessionId, $this->phoneNumber, 420);
		$this->response = $menu;
		$this->printResults();
	}

	public function routeSellerHome() {

		switch ($this->userResponse) {
			case '1':

				break;

			case '2':
				$this->displaySellerFoodHome();
				break;

			case '3':

				break;

			default:
				$this->displaySellerMainMenu(true);
				break;
		}
	}

	public function displaySellerFoodHome($err = false) {

		$menu = "CON Select Option \n";
		$menu .= "1. View My Foods \n";
		$menu .= "2. Add New Food \n";
		$menu .= "3. Update Food \n";
		$menu .= "4. Update Available Foods \n";
		$menu .= "5. Remove Food \n";

		if($err) {

			$menu = str_replace("CON", "CON Invalid Option.", $menu);
		}

		$this->userLevel->updateUserLevel($this->sessionId, $this->phoneNumber, 426);
		$this->response = $menu;
		$this->printResults();

	}

	public function routeManageFood() {

		switch($this->userResponse) {

			case '1':
				print_r("something");
				break;

			case '2':
				$this->food->startFoodSavingProcess($this->phoneNumber, $this->sessionId);
				$this->userLevel->updateUserLevel($this->sessionId, $this->phoneNumber, 4220);
				$this->response = "CON Enter food name";
				$this->printResults();
				break;

			case '3':
				break;

			case '4':
				break;

			case '5':
				break;

			default:
				$this->displaySellerFoodHome(true);
				break;
		}

	}

	public function displayFoodType($err = false) {

		$menu = "CON Select food type\n";
		$menu .= "1. Food \n";
		$menu .= "2. Drink \n";
		$menu .= "3. Dessert";

		if($err) {
			$menu = str_replace("CON", "CON Invalid Option.", $menu);
		}
		$this->response = $menu;
		$this->userLevel->updateUserLevel($this->sessionId, $this->phoneNumber, 4222);
		$this->printResults();
	}

	public function routeSelectFoodType() {

		switch($this->userResponse) {

			case '1':
				$this->food->saveFoodType("Food", $this->sessionId);
				$this->food->activateFood($this->sessionId);
				$this->displayAddNewFood();
				break;

			case '2':
				$this->food->saveFoodType("Drink", $this->sessionId);
				$this->food->activateFood($this->sessionId);
				$this->displayAddNewFood();
				break;

			case '3':
				$this->food->saveFoodType("Dessert", $this->sessionId);
				$this->food->activateFood($this->sessionId);
				$this->displayAddNewFood();
				break;

			default:
				$this->displayFoodType(true);
				break;
		}
	}

	public function displayAddNewFood($err = false) {

		$menu = "CON Add New Food \n";
		$menu .= "1. Yes \n";
		$menu .= "2. No";

		if($err) {
			$menu = str_replace("CON", "CON Invalid Option.", $menu);
		}
		$this->response = $menu;
		$this->userLevel->updateUserLevel($this->sessionId, $this->phoneNumber, 4223);
		$this->printResults();
	}

	public function routeAddNewFood() {

		switch($this->userResponse) {

			case '1':
				$this->food->startFoodSavingProcess($this->phoneNumber, $this->sessionId);
				$this->userLevel->updateUserLevel($this->sessionId, $this->phoneNumber, 4220);
				$this->response = "CON Enter food name";
				$this->printResults();
				break;

			case '2':
				$this->displaySellerFoodHome();
				break;

			default:
				$this->displayAddNewFood(true);
				break;
		}
	}
}
