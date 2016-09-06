<?php
/* Allow Using API */
//header("Access-Control-Allow-Origin: *");

/*Global Includes*/
include"functions.php";

//localhost/api_crud/api.php?f=Authentification&p1=user&p2=user

if(isset($_GET['f'])){
	$f = $_GET['f'];
	if(isset($_GET['p1'])){$p1 = $_GET['p1'];}
	switch($f){
		//Help
		case "Help";Help();break;
		//Authentification
		case "LogIn";LogIn();break;
		case "LogOut";LogOut($p1);break;
		//User
		case "UserAdd";UserAdd($p1);break;
		case "UserDelete";UserDelete($p1);break;
		case "UserUpdate";UserUpdate($p1);break;
		//ProductFamily
		case "ProductFamilyAdd";ProductFamilyAdd($p1);break;
		case "ProductFamilyUpdate";ProductFamilyUpdate($p1);break;
		case "ProductFamilyDelete";ProductFamilyDelete($p1);break;
		case "ProductFamilyGet";ProductFamilyGet();break;
		//ProductSubFamily
		case "ProductSubFamilyAdd";ProductSubFamilyAdd($p1);break;
		case "ProductSubFamilyUpdate";ProductSubFamilyUpdate($p1);break;
		case "ProductSubFamilyDelete";ProductSubFamilyDelete($p1);break;
		//Log
		case "LogRead";LogRead($p1);break;
	}
}
?>