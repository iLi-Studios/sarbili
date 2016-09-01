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
	switch($f){
		//Authentification
		case "LogIn";LogIn($p1, $p2);break;
		case "LogOut";LogOut($p1);break;
		//Log
		case "LogRead";LogRead($p1);break;
		//User
		case "GetUserList";GetUserList();break;
		case "GetUser";GetUser($p1);break;
	}
}
else{
	echo "<b>WRONG HTTP REQUEST</b><br>";
	echo "YOU HAVE TO DEFINE FUNCTION AND PARAMETRES IN YOUR HTTP REQUEST<br>";
	echo "EXEMPLE: api?f=YourFunctionName&p1=parametre1&p2=parametre2...<br>";
	echo "<hr>";
	echo "<br>";
	echo "<h3>Functions List</h3><br>";
	echo "GET_LogIn(loginUser, MD5(passUser))<br>";
	echo "GET_LogOut(Token)<br>";
	echo "GET_LogRead(Token)//Only Admin<br>";
}
?>