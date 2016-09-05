<?php
/* System */
$Timestamp = date("d-m-Y H:i:s");
$NewUserDefaultMdp = "sarbili";
$SystemToken = sha1($NewUserDefaultMdp);
function QueryExcute($Query){
	$MySQLi = new mysqli("localhost", "root", "", "sarbili");
	if (mysqli_connect_errno()) {
		printf("Echec de la connexion : %s\n", mysqli_connect_error());
		exit();
	}
	return $MySQLi->query($Query);
	$MySQLi->close();
}

/* Log */
function LogWrite($idUser, $Description){
	global $Timestamp;
	$x=QueryExcute("INSERT INTO `logsystem` VALUES (NULL, '$idUser', '$Timestamp', '$Description');");
}
function LogRead($Token){
	$RankUser=GetRank($Token);
	if($RankUser=='Administrateur'){
		if($x=QueryExcute("SELECT `logsystem`.`idLog`, `logsystem`.`Description`, `logsystem`.`Timestamp`, `user`.`idUser`, `user`.`loginUser`, `user`.`rankUser`, `user`.`token` FROM `logsystem`, `user` WHERE `logsystem`.`idUser` = `user`.`idUser` Order by `idLog` DESC")){
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
		$reponse["message"] = "Erreur : Vous êtes pas autorisé!";
	}
	print(json_encode($reponse));
}

/* Authentification */
function TokenGenerate($idUser){
	$Token = md5(uniqid($idUser, true));
	$x=QueryExcute("UPDATE `user` SET `token`='$Token' WHERE `idUser`='$idUser'");
}
function LogIn(){
	if(isset($_GET['Login'])&&isset($_GET['Pass'])){
		$Login=addslashes($_GET['Login']);
		$Pass=addslashes($_GET['Pass']);
		if ($x = QueryExcute("SELECT * FROM `user` WHERE `loginUser`='$Login' AND `passUser`='$Pass'")) {
			if($row_x=$x->fetch_assoc()){
				// Test si l'utilisateur est déjà connecté pour ne pas ecraté la token
					if(!isset($row_x["token"])){
						TokenGenerate($row_x["idUser"]);
						//Récupération de ligne compléte avec Token en json
						if ($y = QueryExcute("SELECT * FROM `user` WHERE `loginUser`='$Login' AND `passUser`='$Pass'")) {
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
						$reponse["message"] = "Erreur: Vous êtes déjà connecté";
					}
			}
			else{
				$reponse["success"] = 0;
				$reponse["message"] = "Erreur : Combinaison incorrect!";
			}
			$x->close();
		}
		else{
			$reponse["success"] = 0;
			$reponse["message"] = "Erreur : Base de données!";		
		}
	}
	else{
		$reponse["success"] = 0;
		$reponse["message"] = "Erreur : Champ(s) manquant(s)";
	}
	print(json_encode($reponse));
}
function LogOut($Token){
	if ($x = QueryExcute("SELECT * FROM `user` WHERE `token`='$Token'")) {
		// cas ou il y a un utilisateur connecté avec cette token
		if($row=$x->fetch_assoc()){
			$idUser=$row["idUser"];
			$y=QueryExcute("UPDATE `user` SET `token`=NULL WHERE `idUser`='$idUser'");
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
	$x->close();
}
function GetUserLogin($idUser){
	$x = QueryExcute("SELECT `loginUser` FROM `user` WHERE `idUser`='$idUser'")->fetch_assoc();
	return $x["loginUser"];
	$x->close();
}
function IdFromToken($Token){
	$x = QueryExcute("SELECT `idUser` FROM `user` WHERE `token`='$Token'")->fetch_assoc();
	return $x["idUser"];
	$x->close();
}
function UserAdd($Token){
	global $NewUserDefaultMdp;
	$RankUser=GetRank($Token);
	if($RankUser=='Administrateur'){
		$idAdmin=IdFromToken($Token);
		/*
		Requête HTTP Post
		*/
		// tableau de réponse JSON (array)
		$reponse=array();
		// tester si les champs sont valides
		if(isset($_GET['Login'])){
			$LoginUser=addslashes($_GET['Login']);
			if($x=QueryExcute("INSERT INTO `user` values (NULL, '$LoginUser', MD5('".$NewUserDefaultMdp."'), 'Utilisateur', NULL)")){
				$reponse["success"] = 1;
				$reponse["message"] = 'Utilisateur : '.$LoginUser.' a été ajouté avec succès';
				LogWrite($idAdmin, "Nouveau utilisateur : ".$LoginUser);
			}
			else{
				$reponse["success"] = 0;
				$reponse["message"] = "Erreur : Nom d'utilisateur est déjà pris";
			}	
		}
		else{
			$reponse["success"] = 0;
			$reponse["message"] = "Erreur : Champ(s) manquant(s)";
		}
	}
	else{
		$reponse["success"] = 0;
		$reponse["message"] = "Erreur : Vous êtes pas autorisé!";
	}
	print(json_encode($reponse));
}
function UserUpdate($Token){
	$RankUser=GetRank($Token);
	if($RankUser=='Administrateur'){
		$idAdmin=IdFromToken($Token);
		/*
		Requête HTTP Post
		*/
		// tableau de réponse JSON (array)
		$reponse=array();
		// tester si les champs sont valides
		if(isset($_GET['idUser'])&&isset($_GET['NewLoginUser'])){
			$idUser=addslashes($_GET['idUser']);
			$NewLoginUser=addslashes($_GET['NewLoginUser']);
			$LoginUser=GetUserLogin($idUser);
			//test si l'utilisateur existe ou pas && l'admin ne peut pas se supprimer (system 1 admin n utilisateur)
			$y=QueryExcute("SELECT COUNT(*) FROM `user` WHERE `idUser`='$idUser'")->fetch_array();
			if($y[0]>0){
				$z=QueryExcute("SELECT `rankUser` FROM `user` WHERE `idUser`='$idUser'")->fetch_assoc();
				$RankUserFromIdUser=$z["rankUser"];
				if($x=QueryExcute("UPDATE `user` SET `loginUser` = '".$NewLoginUser."' WHERE `idUser` =".$idUser)){
					$reponse["success"] = 1;
					$reponse["message"] = 'Mise à jour nom d\'utilisateur de '.$LoginUser.' a '.$NewLoginUser;
					LogWrite($idAdmin, "Mise a jour nom d\'utilisateur de ".$LoginUser." a ".$NewLoginUser);	
				}
				else{
					$reponse["success"] = 0;
					$reponse["message"] = "Erreur : Modification utilisateur";
				}	
			}
			else{
				$reponse["success"] = 0;
				$reponse["message"] = 'Erreur : Utilisateur non trouvé!';
			}
		}
		else{
			$reponse["success"] = 0;
			$reponse["message"] = "Erreur : Champ(s) manquant(s)";
		}
	}
	else{
		$reponse["success"] = 0;
		$reponse["message"] = "Erreur : Vous êtes pas autorisé!";
	}
	print(json_encode($reponse));
}
function UserDelete($Token){
	$RankUser=GetRank($Token);
	if($RankUser=='Administrateur'){
		$idAdmin=IdFromToken($Token);
		/*
		Requête HTTP Post
		*/
		// tableau de réponse JSON (array)
		$reponse=array();
		// tester si les champs sont valides
		if(isset($_GET['idUser'])){
			$idUser=addslashes($_GET['idUser']);
			$LoginUser=GetUserLogin($idUser);
			//test si l'utilisateur existe ou pas && l'admin ne peut pas se supprimer (system 1 admin n utilisateur)
			$y=QueryExcute("SELECT COUNT(*) FROM `user` WHERE `idUser`='$idUser'")->fetch_array();
			if($y[0]>0){
				$z=QueryExcute("SELECT `rankUser` FROM `user` WHERE `idUser`='$idUser'")->fetch_assoc();
				$RankUserFromIdUser=$z["rankUser"];
				if($RankUserFromIdUser!='Administrateur'){
					if($x=QueryExcute("DELETE FROM `user` WHERE `user`.`idUser` = ".$idUser)){
						$reponse["success"] = 1;
						$reponse["message"] = 'Utilisateur : '.$LoginUser.' a été supprimé avec succès';
						LogWrite($idAdmin, "Suppression d\'utilisateur : ".$LoginUser);	
					}
					else{
						$reponse["success"] = 0;
						$reponse["message"] = "Erreur : Suppression utilisateur";
					}	
				}
				else{
					$reponse["success"] = 0;
					$reponse["message"] = 'Erreur : L\'administrateur ne peut pas se supprimer';
				}
			}
			else{
				$reponse["success"] = 0;
				$reponse["message"] = 'Erreur : Utilisateur non trouvé!';
			}
		}
		else{
			$reponse["success"] = 0;
			$reponse["message"] = "Erreur : Champ(s) manquant(s)";
		}
	}
	else{
		$reponse["success"] = 0;
		$reponse["message"] = "Erreur : Vous êtes pas autorisé!";
	}
	print(json_encode($reponse));
}

/*Productfamily */
function ProductFamilyGet(){
	$reponse=array();
	$x=QueryExcute("SELECT COUNT(*) FROM `productfamily`")->fetch_array();
	if($x[0]>0){
		if($y=QueryExcute("SELECT * FROM `productfamily`")){
			$reponse["success"] 	= 1;
			$reponse["message"] 	= "";
			while($row_y=$y->fetch_assoc()){
				$reponse_child[]=$row_y;
				$reponse["ProductFamily"]=$reponse_child;
			}
		$y->close();
		}
	}
	else{
		$reponse["success"] = 0;
		$reponse["message"] = "Erreur : Pas de resultat!";
	}
	print(json_encode($reponse));
}
function ProductFamilyAdd($Token){
	$RankUser=GetRank($Token);
	if($RankUser=='Administrateur'){
		$idAdmin=IdFromToken($Token);
		/*
		Requête HTTP Post
		*/
		// tableau de réponse JSON (array)
		$reponse=array();
		// tester si les champs sont valides
		if(isset($_GET['NameProductFamily'])){
			$NameProductFamily=addslashes($_GET['NameProductFamily']);
			//test si NameProductFamily existe déjà
			$x=QueryExcute("SELECT COUNT(*) FROM `productfamily` WHERE `NameProductFamily`='$NameProductFamily'")->fetch_array();
			if($x[0]=0){
				if($y=QueryExcute("INSERT INTO `productfamily` VALUES (NULL, '$NameProductFamily');")){
					$reponse["success"] = 1;
					$reponse["message"] = 'Famille de produit : '.$NameProductFamily.' a été ajouté avec succès';
					LogWrite($idAdmin, "Nouvelle famille de produit : ".$NameProductFamily);
				}
				else{
					$reponse["success"] = 0;
					$reponse["message"] = "Erreur : Insertion des données";
				}
			}
			else{
				$reponse["success"] = 0;
				$reponse["message"] = "Erreur : Famille de produit déjà existante!";
			}
		}
		else{
			$reponse["success"] = 0;
			$reponse["message"] = "Erreur : Champ(s) manquant(s)";
		}
	}
	else{
		$reponse["success"] = 0;
		$reponse["message"] = "Erreur : Vous êtes pas autorisé!";
	}
	print(json_encode($reponse));
}
function ProductFamilyUpdate($Token){
	$RankUser=GetRank($Token);
	if($RankUser=='Administrateur'){
		$idAdmin=IdFromToken($Token);
		/*
		Requête HTTP Post
		*/
		// tableau de réponse JSON (array)
		$reponse=array();
		// tester si les champs sont valides
		if(isset($_GET['idProductFamily'])&&isset($_GET['NewNameProductFamily'])){
			$idProductFamily=addslashes($_GET['idProductFamily']);
			$NewNameProductFamily=addslashes($_GET['NewNameProductFamily']);
			//test si la famille existe
			$x=QueryExcute("SELECT COUNT(*) FROM `productfamily` WHERE `idProductFamily`='$idProductFamily'")->fetch_array();
			if($x[0]>0){
				// récupération de l'ancien nom de faille avant le mise a jour
				$y=QueryExcute("SELECT `NameProductFamily` FROM `productfamily` WHERE `idProductFamily`='$idProductFamily'")->fetch_assoc();
				$LastNameProductFamily=$y["NameProductFamily"];
				// S'il y a pas de diffirence entre l'ancien et le nouveau champ pas d'execution de requêtte
				if($LastNameProductFamily!=$NewNameProductFamily){
					if($z=QueryExcute("UPDATE `productfamily` SET `NameProductFamily` = '".$NewNameProductFamily."' WHERE `idProductFamily` =".$idProductFamily)){
						$reponse["success"] = 1;
						$reponse["message"] = 'Mise à jour de la famille du produit, de '.$LastNameProductFamily.' a '.$NewNameProductFamily;
						LogWrite($idAdmin, "Mise à jour de la famille du produit, de ".$LastNameProductFamily." a ".$NewNameProductFamily);	
					}
					else{
						$reponse["success"] = 0;
						$reponse["message"] = "Erreur : Modification de la famille du produit";
					}	
				}
				else{
					$reponse["success"] = 0;
					$reponse["message"] = 'Erreur : Pas de mise à jour!';
				}
			}
			else{
				$reponse["success"] = 0;
				$reponse["message"] = 'Erreur : Famille de produit non trouvé!';
			}
		}
		else{
			$reponse["success"] = 0;
			$reponse["message"] = "Erreur : Champ(s) manquant(s)";
		}
	}
	else{
		$reponse["success"] = 0;
		$reponse["message"] = "Erreur : Vous êtes pas autorisé!";
	}
	print(json_encode($reponse));
}
function ProductFamilyDelete($Token){
	$RankUser=GetRank($Token);
	if($RankUser=='Administrateur'){
		$idAdmin=IdFromToken($Token);
		/*
		Requête HTTP Post
		*/
		// tableau de réponse JSON (array)
		$reponse=array();
		// tester si les champs sont valides
		if(isset($_GET['idProductFamily'])){
			$idProductFamily=addslashes($_GET['idProductFamily']);
			//test si la famille existe
			$x=QueryExcute("SELECT COUNT(*) FROM `productfamily` WHERE `idProductFamily`='$idProductFamily'")->fetch_array();
			if($x[0]>0){
				// récupération de l'ancien nom de faille avant la suppression
				$y=QueryExcute("SELECT `NameProductFamily` FROM `productfamily` WHERE `idProductFamily`='$idProductFamily'")->fetch_assoc();
				$LastNameProductFamily=$y["NameProductFamily"];
				if($z=QueryExcute("DELETE FROM `productfamily` WHERE `idProductFamily` = ".$idProductFamily)){
					$reponse["success"] = 1;
					$reponse["message"] = 'Suppression de la famille du produit '.$LastNameProductFamily;
					LogWrite($idAdmin, "Suppression de la famille du produit  ".$LastNameProductFamily);	
				}
				else{
					$reponse["success"] = 0;
					$reponse["message"] = "Erreur : Suppression de la famille du produit";
				}	
			}
			else{
				$reponse["success"] = 0;
				$reponse["message"] = 'Erreur : Famille de produit non trouvé!';
			}
		}
		else{
			$reponse["success"] = 0;
			$reponse["message"] = "Erreur : Champ(s) manquant(s)";
		}
	}
	else{
		$reponse["success"] = 0;
		$reponse["message"] = "Erreur : Vous êtes pas autorisé!";
	}
	print(json_encode($reponse));
}

/* Help */
function Help(){
	include "help.html";
}

?>
