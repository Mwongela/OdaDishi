<?php

if (!empty($_POST)) {

	require_once "controller/MainController.php";

	$sessionId = $_POST['sessionId'];
	$serviceCode = $_POST['serviceCode'];
	$phoneNumber = $_POST['phoneNumber'];
	$text = $_POST['text'];
	$textArray = explode('*', $text);
	$userResponse = trim(end($textArray));

	$mainController = new MainController();

	$response = $mainController->displayMainMenu();

	header("Content-type: text/plain");

	echo $response;
}