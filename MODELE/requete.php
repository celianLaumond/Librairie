<?php
function getcatalogue(){
    $connexion = new PDO('mysql:host=localhost;dbname=abonne', 'root', 'root');
    // Requête SQL pour récupérer tous les livres
    $requete = $connexion->query('SELECT * FROM livre');

    // Création d'un tableau pour stocker les livres
    $catalogue = array();

    // Récupération des résultats de la requête
    while ($livre = $requete->fetch()) {
        $catalogue[] = $livre;
    }

    // Fermeture de la connexion à la base de données
    $connexion = null;

    return $catalogue;
}

function findbook($titre, $auteur, $editeur, )
{
    $connexion = new PDO('mysql:host=localhost;dbname=abonne', 'root', 'root');
    $requete = $connexion->prepare("SELECT livre.titre, auteur.nom AS auteur_nom, editeur.nom, emprunt.date_emprunt AS editeur_nom, emprunt.disponible
FROM livre  
JOIN auteur ON auteur.id = livre.auteur_id
JOIN editeur ON editeur.id = livre.editeur_id
JOIN emprunt ON emprunt.id_livre = livre.id
WHERE livre.titre LIKE :titre AND auteur.nom LIKE :auteur AND editeur.nom LIKE :editeur 
ORDER BY emprunt.date_emprunt DESC
limit 1

");

// Associer les paramètres
    $requete->bindParam(':titre', $titre, PDO::PARAM_STR);
    $requete->bindParam(':auteur', $auteur, PDO::PARAM_STR);
    $requete->bindParam(':editeur', $editeur, PDO::PARAM_STR);


    $requete->execute();

    $catalogue = $requete->fetchAll(PDO::FETCH_ASSOC);

    $connexion = null;

    return $catalogue;


}

function findSub($name, $surname, $country, $sub)
{
    try {
        $connexion = new PDO('mysql:host=localhost;dbname=abonne', 'root', 'root');

        // Utilisez des alias de table pour éviter les ambiguïtés dans les colonnes
        $requete = $connexion->prepare("SELECT abonne.nom, abonne.prenom, abonne.ville, abonne.date_naissance, abonnement.date_abonnement, abonnement.date_expiration, abonnement.date_fin_abo
        FROM abonne
        INNER JOIN abonnement ON abonne.id = abonnement.abonne_id
        WHERE abonne.nom LIKE :name AND abonne.prenom LIKE :surname AND abonne.ville LIKE :country AND abonnement.sub LIKE :sub
        ");

        // Liez les paramètres après avoir préparé la requête
        $requete->bindParam(':name', $name, PDO::PARAM_STR);
        $requete->bindParam(':surname', $surname, PDO::PARAM_STR);
        $requete->bindParam(':country', $country, PDO::PARAM_STR);
        $requete->bindParam(':sub', $sub, PDO::PARAM_STR);


        // Utilisez fetchAll après l'exécution de la requête
        $catalogue = $requete->fetchAll(PDO::FETCH_ASSOC);

        // Fermez la connexion après utilisation
        $connexion = null;

        return $catalogue;
    } catch (PDOException $e) {
        // Gérez les erreurs de base de données ici (log, affichage, etc.)
        die("Erreur: " . $e->getMessage());
    }
}
    function findallsub(){
        $connexion = new PDO('mysql:host=localhost;dbname=abonne', 'root', 'root');
        $requete=$connexion->query('select * from abonne');
        $result = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

function updateSub($id,$name, $surname, $country, $numberPostal, $adresse, $birth, $sub, $date_sub, $date_exp, $date_fin) {
    try {
        $connexion = new PDO('mysql:host=localhost;dbname=abonne', 'root', 'root');

        $requete = $connexion->prepare("UPDATE abonne
                                             SET 
                                            abonne.nom = :name,
                                            abonne.prenom = :surname,
                                            abonne.ville = :country,
                                            abonne.code_postal = :numberPostal,
                                            abonne.adresse = :adresse,
                                            abonne.date_naissance = :birth,
                                            abonnement.sub = :sub,
                                            abonnement.date_abonnement = :date_sub,
                                            abonnement.date_expiration = :date_exp,
                                            abonnement.date_fin_abo = :date_fin
                                        where abonne.id = :id
                                        ");

        $requete->bindParam(':name', $name, PDO::PARAM_STR);
        $requete->bindParam(':surname', $surname, PDO::PARAM_STR);
        $requete->bindParam(':country', $country, PDO::PARAM_STR);
        $requete->bindParam(':numberPostal', $numberPostal, PDO::PARAM_STR);
        $requete->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $requete->bindParam(':birth', $birth, PDO::PARAM_STR);
        $requete->bindParam(':sub', $sub, PDO::PARAM_STR);
        $requete->bindParam(':date_sub', $date_sub, PDO::PARAM_STR);
        $requete->bindParam(':date_exp', $date_exp, PDO::PARAM_STR);
        $requete->bindParam(':date_fin', $date_fin, PDO::PARAM_STR);
        $requete->bindParam(':id', $id, PDO::PARAM_STR);

        $requete->execute();

        $connexion = null;

        return true; // Ou vous pouvez retourner quelque chose pour indiquer le succès de la mise à jour
    } catch (PDOException $e) {
        // Gérez les erreurs de base de données ici (log, affichage, etc.)
        die("Erreur: " . $e->getMessage());
        // Ou retournez quelque chose pour indiquer l'échec de la mise à jour
    }


    }
function selectebookofsub($id_abo) {
    try {
        $connexion = new PDO('mysql:host=localhost;dbname=abonne', 'root', 'root');
        $requete = $connexion->prepare('SELECT livre.titre, auteur.nom AS nom_auteur, editeur.nom AS nom_editeur
                                       FROM livre
                                       JOIN abonne ON abonne.id = livre.id_abonne
                                       JOIN auteur ON auteur.id = livre.auteur_id
                                       JOIN editeur ON editeur.id = livre.editeur_id
                                       WHERE abonne.id = :id_abo');

        $requete->bindParam(':id_abo', $id_abo, PDO::PARAM_INT);
        $requete->execute();

        $resultat = $requete->fetchAll(PDO::FETCH_ASSOC);

        $connexion = null;

        return $resultat;
    } catch (PDOException $e) {
        // Gérez les erreurs de base de données ici (log, affichage, etc.)
        die("Erreur: " . $e->getMessage());
        // Ou retournez quelque chose pour indiquer l'échec de la sélection
    }
}
function getStatusUser($login, $password) {
    try {
        $connexion = new PDO('mysql:host=localhost;dbname=abonne', 'root', 'root');

        // Préparez la requête SQL
        $query = "SELECT statut FROM utilisateur WHERE login = :login AND mot_de_passe = :password";

        // Préparez la requête SQL avec PDO
        $stmt = $connexion->prepare($query);

        // Liez les paramètres
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);

        // Exécutez la requête
        $stmt->execute();

        // Récupérez le résultat
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fermez la connexion
        $conn = null;

        // Retournez le statut de l'utilisateur ou null si l'utilisateur n'est pas trouvé
        return $result ? $result['statut'] : null;
    } catch (PDOException $e) {
        // Gérez les erreurs de base de données ici (log, affichage, etc.)
        die("Erreur: " . $e->getMessage());
        // Ou retournez quelque chose pour indiquer l'échec de la requête
    }
}

function getInfoAbonne($idUtilisateur,$mot_de_passe) {
    try {
        $connexion = new PDO('mysql:host=localhost;dbname=abonne', 'root', 'root');

        // Préparez la requête SQL pour récupérer les informations de l'abonné
        $query = "SELECT id_utilisateur, mot_de_passe,statut,email FROM utilisateur WHERE id_utilisateur = :id_utilisateur AND mot_de_passe = :mot_de_passe";

        // Préparez la requête SQL avec PDO
        $stmt = $connexion->prepare($query);

        // Liez le paramètre
        $stmt->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
        $stmt->bindParam(':mot_de_passe', $mot_de_passe, PDO::PARAM_STR);

        // Exécutez la requête
        $stmt->execute();

        // Récupérez le résultat sous forme de tableau associatif
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fermez la connexion
        $conn = null;

        return $result;

    } catch (PDOException $e) {
        // Gérez les erreurs de base de données ici (log, affichage, etc.)
        die("Erreur: " . $e->getMessage());
    }
}
function selectPreferredBooks($id_abonne) {
    try {
        $connexion = new PDO('mysql:host=localhost;dbname=abonne', 'root', 'root');
        $requete = $connexion->prepare("
           SELECT titre 
FROM livre
         JOIN emprunt  ON emprunt.id_livre = livre.id
WHERE livre.categorie =
      (
          SELECT livre.categorie
          FROM emprunt
                   JOIN livre  ON emprunt.id_livre = livre.id
          WHERE emprunt.id_abonne = :id
            AND livre.categorie <> ''
          GROUP BY livre.categorie
          ORDER BY COUNT(*) DESC
          LIMIT 1
      )
  AND emprunt.date_emprunt >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
  AND emprunt.disponible = 1
GROUP BY livre.titre
ORDER BY COUNT(*) DESC
LIMIT 5

        ");

        $requete->bindParam(':id_abonne', $id_abonne, PDO::PARAM_INT);
        $requete->execute();

        $resultat = $requete->fetchAll(PDO::FETCH_ASSOC);

        $connexion = null;

        return $resultat;
    } catch (PDOException $e) {
        // Gérez les erreurs de base de données ici (log, affichage, etc.)
        die("Erreur: " . $e->getMessage());
        // Ou retournez quelque chose pour indiquer l'échec de la sélection
    }
}
