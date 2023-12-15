<?php
require_once '../MODELE/requete.php';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    Serviceconnexion();
 //$livre=find();
 //require_once '../VUE/resultat.php';
}
function Serviceconnexion() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];
   $result= getInfoAbonne($login, $password);
    }

    if ($result) {

        $idUtilisateur = $result['id_utilisateur'];
        $statut = $result['statut'];
        if($statut='abonne'){
            require_once '../VUE/menu.php';
        }
        elseif ($statut='gestionaire'){
            require_once '../VUE/findSub.php';
        }

    } else {
        echo 'Mauvais identifiants';
        require_once '../VUE/connexion.php';
    }


}
function appelAcceuil() {
    require_once '../MODELE/requete.php';
    $catalogue = getcatalogue();
    require_once '../VUE/resultat.php';
}
function appelEmprunts() {

    require_once '../vue/emprunts.php';
}
function find(){

    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $editeur = $_POST['editeur'];
  
    $livre = findbook($titre, $auteur, $editeur, );
    require_once '../vue/resultat.php';
    return $livre;


}
