<?php
/* Allow Using API */
//header("Access-Control-Allow-Origin: *");

/*Global Includes*/
include"functions.php";

//localhost/api_crud/api.php?f=Authentification&p1=user&p2=user

if(isset($_GET['f'])){
	$f = $_GET['f'];
	if(isset($_GET['p1'])){$p1 = $_GET['p1'];}
	if(isset($_GET['p2'])){$p2 = $_GET['p2'];}
	if(isset($_GET['p3'])){$p3 = $_GET['p3'];}
	if(isset($_GET['p4'])){$p4 = $_GET['p4'];}
	if(isset($_GET['p5'])){$p5 = $_GET['p5'];}
	switch($f){
		//Authentification
		case "LogIn";LogIn($p1, $p2);break;
		case "LogOut";LogOut($p1);break;
		//User
		case "UserAdd";UserAdd($p1);break;
		case "UserDelete";UserDelete($p1);break;
		case "UserUpdate";UserUpdate($p1);break;
		//Log
		case "LogRead";LogRead($p1);break;
	}
	Help();
}
?>