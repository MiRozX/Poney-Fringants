<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// récupérer les données d'enregistrement : pseudo, email, mot de passe (confirmé)
$pseudo = $_POST['pseudo']; 
$email = $_POST['email'];
$password = $_POST['mdp'];
$confirmation = $_POST['mdp-confirmation'];

// valider les données d'enregistrement 
// On utilise la fonction filter_var : https://www.php.net/manual/fr/function.filter-var.php
// Avec le filtre de validation des emails
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Le format de l'adresse est incorrect";
    exit; 
}

// vérifier le nombre maximal de caractères par rapport aux types renseigné lors de la création de la BDD
if(strlen($pseudo) > 255 || strlen($email) > 255) {

    echo "Le pseudo ou le mail est trop long"; 
    exit; 
}
// vérifier la présence des mots de passe et l'égalité de mdp et de sa confirmation
if( $password != $confirmation) {

    echo "Les mots de passes sont différents"; 
    exit; 
}

try {

    // connexion à la base
    include_once('bdd.php');
    // configuration (pour les exceptions)
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $rqt = "SELECT * FROM utilisateurs WHERE pseudo=:pseudo OR email=:email";
    // on prépare la requête
    $requetePreparee = $connexion->prepare($rqt);
    // on associe les valeurs (du formulaire) aux paramètres de notre requête préparée (ceux qui commencent pas `:` dans notre requête)
    $requetePreparee->bindParam(':pseudo', $pseudo);
    $requetePreparee->bindParam(':email', $email);
    // on execute la requête
    $requetePreparee->execute();
  
    // Si on a un résultat (ou plus), ça veut dire que le pseudo ou l'email est déjà pris
    if($requetePreparee->fetch() != false) {
        // TODO : message d'erreur mail ou pseudo déjà pris, 
        // on quitte
        echo "Email ou pseudo déjà pris"; 
        exit;
    }

    // hashage du mot de passe
    $hash = password_hash($password, PASSWORD_DEFAULT); 

    // on écrit la requête d'insertion
    $rqt = "INSERT INTO utilisateurs(pseudo, email, password) VALUES(:pseudo, :email, :password)";
    // on prépare la requête
    $requetePreparee = $connexion->prepare($rqt);
    // on associe les valeurs (du formulaire) aux paramètres de notre requête préparée (ceux qui commencent pas `:` dans notre requête)
    $requetePreparee->bindParam(':pseudo', $pseudo);
    $requetePreparee->bindParam(':email', $email);
    $requetePreparee->bindParam(':password', $hash);

    // on execute la requête
    $requetePreparee->execute();
    // on vérifie le nombre de ligne insérée (1 normalement)
    $nbLignesModifiee = $requetePreparee->rowCount();
    if($nbLignesModifiee != 1) {
        // TODO : a problème, message, quitte
        echo "Problème lors de l'enregistrement";
        exit;
    } else {
        
        header('Location: http://localhost:82/home.html');

    }

} catch(Exception $e) { // Si on "attrape" exception, c'est qu'il y a eu un problème 
    // On affiche le message d'erreur et on quitte
    echo $e->getMessage(); //:waring: :attention: :achtung: On ne fait pas un sur du code en production !!!!!
}