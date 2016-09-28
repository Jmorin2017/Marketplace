<?php 

include $_SERVER['DOCUMENT_ROOT'] . "/libs/header.php";

if(isset($_SESSION['userID'])){

	$sessionUserID = $_SESSION['userID'];

	if($controller->logout($sessionUserID) == true){

		$controller->redirect("http://localhost/");

	}


}
