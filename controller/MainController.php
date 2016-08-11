<?php

class MainController {

	function __construct() {

	}

	public function displayMainMenu() {

		$menu = "";
		$menu .= "CON Welcome to Oda Dishi \n";
		$menu .= "1. Order Food \n";
		$menu .= "2. Order Drink \n";
		$menu .= "3. Order Dessert\n";
		$menu .= "4. Seller\'s Portal";

		return $menu;
	}

	public function displayOrderFood() {


	}

	public function displayOrderDrink() {


	}

	public function displayOrderDessert() {


	}

	public function displaySellerPortal() {


	}
}