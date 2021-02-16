<?php 
    include_once('bdd.php'); // n'inclut le fichier que s'il n'a pas déjà été inclut

    // Récupérer les données du formulaire : (méthode POST)
    var_dump($_POST);
    //header('Location: http://localhost:82/profil.html');

    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = $_POST['motdepasse']; 
    try {
        // Connexion à la base de donnée
        $connexionSQL = new PDO('mysql:host=' . $db_url .';dbname='. $db_name, $db_user, $db_pass );
        $connexionSQL->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        
        // Vérifier que son pseudo est bien sur la même ligne que son mot de passe

        // On prépare une requête, en construisant une chaîne de caractère contenant la requête SQL
        $rqt = "SELECT pseudo, password FROM utilisateurs WHERE pseudo = :pseudo LIMIT 1"; //:pseudo est un paramètre de requête préparée dont on donnera la valeur au moment de l'éxectution
        // LIMIT 1 sert à être sûr qu'on ne récupère qu'une seule ligne; 
        
        $statement = $connexionSQL->prepare($rqt); 
        // On indique la valeur de :pseudo en passant celle qu'on a récupéré dans les données soumises du formulaires
        $statement->bindParam(':pseudo', $pseudo);
        //On peut executer la requête 
        $statement->execute(); 

        // On récupère les résultats : fetch() permet de récupérer une ligne, fetchAll() toutes les lignes
       
        $resultat = $statement->fetch(PDO::FETCH_ASSOC);
        var_dump($resultat);

    } catch (Exception $e) {
        echo $e->getMessage();
        
    }

    

    // S'il ne contient rien, le pseudo n'a pas été trouvé en base.
    if(!empty($resultat['pseudo']) && !empty($resultat['email'] && !empty($resultat['password']))) {
        $hash = $resultat['password']; 

        // On verifie le mdp
        $ok = password_verify($password, $hash);
    } 

    if ($ok) {
        echo('Yes tu t co');
    }
    
    else {
        echo('arraah');
    }
  
    
?>