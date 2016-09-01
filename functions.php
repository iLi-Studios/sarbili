<?php
/* System */
$Timestamp = date("d-m-Y H:i:s");
function QueryExcute($Query){
	$MySQLi = new mysqli("localhost", "root", "", "sarbili");
	if (mysqli_connect_errno()) {
		printf("Echec de la connexion : %s\n", mysqli_connect_error());
		exit();
	}
	return $MySQLi->query($Query);
	$MySQLi->close();
}

/* Authentification */
function TokenGenerate($idUser){
	$Token = md5(uniqid($idUser, true));
	QueryExcute("UPDATE `user` SET `token`='$Token' WHERE `idUser`='$idUser'");
}
function LogIn($loginUser, $passUser){
	if ($x = QueryExcute("SELECT * FROM `user` WHERE `loginUser`='$loginUser' AND `passUser`='$passUser'")) {
		if($row_x=$x->fetch_assoc()){
			// Test si l'utilisateur est déjà connecté pour ne pas ecraté la token
				if(!isset($row_x["token"])){
					TokenGenerate($row_x["idUser"]);
					//Récupération de ligne compléte avec Token en json
					if ($y = QueryExcute("SELECT * FROM `user` WHERE `loginUser`='$loginUser' AND `passUser`='$passUser'")) {
						if($row_y=$y->fetch_assoc()){
							$reponse["success"] = 1;
							$reponse["message"] = "Bienvenue ".$row_y["loginUser"];
							$reponse_child[]=$row_y;
							/*$reponse_child["idUser"] = $row_y["idUser"];
							$reponse_child["LoginUser"] = $row_y["loginUser"];
							$reponse_child["RankUser"] = $row_y["rankUser"];
							$reponse_child["Token"] = $row_y["token"];*/
							$reponse["User"]=$reponse_child;
							LogWrite($row_x["idUser"], "Conexion");
						}
					}
					$y->close();
				}
				else{
					$reponse["success"] = 0;
					$reponse["message"] = "Vous êtes déjà connecté";
				}
		}
		else{
			$reponse["success"] = 0;
			$reponse["message"] = "Combinaison incorrect!";
		}
		$x->close();
	}
	print(json_encode($reponse));
}
function LogOut($Token){
	if ($x = QueryExcute("SELECT * FROM `user` WHERE `token`='$Token'")) {
		// cas ou il y a un utilisateur connecté avec cette token
		if($row=$x->fetch_assoc()){
			$idUser=$row["idUser"];
			QueryExcute("UPDATE `user` SET `token`=NULL WHERE `idUser`='$idUser'");
			LogWrite($idUser, "Deconexion");
			$reponse["success"] = 1;
			$reponse["message"] = "Déconnection!";
		}
		else{
		$reponse["success"] = 0;
		$reponse["message"] = "Utilisateur non connecté!";
		}
	}
	$x->close();
	print(json_encode($reponse));
}

/* User */
function GetRank($Token){
	$x = QueryExcute("SELECT `rankUser` FROM `user` WHERE `token`='$Token'")->fetch_assoc();
	return $x["rankUser"];
}

function GetUserList(){
		if($x=QueryExcute("SELECT * FROM `user`")){
			$reponse["success"] = 1; 
			$reponse["message"] = "";
			while($row_x=$x->fetch_assoc()){
				$reponse_child[]=$row_x;
				$reponse["Users"]=$reponse_child;
			}
		$x->close();
		}
		else{
			$reponse["success"] = 0; 
			$reponse["message"] = "Pas d'utilisateurs!";
		}
		print(json_encode($reponse));
}
function GetUser($idUser){
	if ($x = QueryExcute("SELECT * FROM `user` WHERE `idUser`='$idUser'")) {
		if($row=$x->fetch_assoc()){
			$reponse["success"] = 1; 
			$reponse["message"] = "";
			$reponse["loginUser"] = $row["loginUser"];
			$reponse["rankUser"] = $row["rankUser"];
			$reponse["token"] = $row["token"];
		}
		else{
			$reponse["success"] = 0; 
			$reponse["message"] = "Utilisateur introuvable!";
		}
		$x->close();
		print(json_encode($reponse));
	}
}

/* Log */
function LogWrite($idUser, $Description){
	global $Timestamp;
	$x=QueryExcute("INSERT INTO `logsystem` VALUES (NULL, '$idUser', '$Timestamp', '$Description');");
}
function LogRead($Token){
	$RankUser=GetRank($Token);
	if($RankUser=='admin'){
		if($x=QueryExcute("SELECT * FROM `logsystem`")){
			$reponse["success"] 	= 1;
			$reponse["message"] 	= "";
			while($row_x=$x->fetch_assoc()){
				$reponse_child[]=$row_x;
				$reponse["logSystem"]=$reponse_child;
			}
		$x->close();
		}
	}
	else{
		$reponse["success"] = 0;
		$reponse["message"] = "Vous êtes pas autorisé!";
	}
	print(json_encode($reponse));
}
?>

