<?php

//Contient les informations pour la connexion de la base de donnée
$db_url = "mysql:host=localhost;dbname=Poneyfringants";
$db_name = "Poneyfringants";
$db_user = "enzo";
$db_pass = "EnzoAdmin";

$connexion = new PDO($db_url, $db_user, $db_pass);
$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

?>
