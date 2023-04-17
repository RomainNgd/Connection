<?php
session_start();

define("URL", str_replace("index.php","",(isset($_SERVER['HTTPS'])? "https" : "http").
    "://".$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"]));

require_once("./Controllers/visiteur/visiteur.controller.php");
require_once("./Controllers/utilisateurs/utilisateurs.controller.php");
require_once("./Controllers/MainController.controller.php");
require_once("./Controllers/Security.php");
$mainController = new MainController();
$visiteurController = new VisiteurController();
$utilisateurController = new UtilisateursController();

try {
    if(empty($_GET['page'])){
        $page = "accueil";
    } else {
        $url = explode("/", filter_var($_GET['page'],FILTER_SANITIZE_URL));
        $page = $url[0];
    }

    switch($page){
        case "accueil" : $visiteurController->accueil();
            break;
        case "login": $visiteurController->login();
            break;
            case "validation_login" :
                if(empty($_POST['login'])&& !empty($_POST['password'])){
                    $login = Security::secureHTML($_POST['login']);
                    $password = Security::secureHTML($_POST['password']);
                    $utilisateurController->validation_login($login, $password);
                }else{
                    Toolbox::ajouterMessageAlerte(
                        "Login ou mot de passe non reseigné",
                        Toolbox::COULEUR_ROUGE
                    );
                    header('Location:'.URL."login");
                }
                break;
            
            case "compte" :
                switch($url[1]){
                    case "profil" :
                        $utilisateurController->profil();
                        break;
                        default : throw new RuntimeException("La page n'existe pas");
                };
                break;
                default : throw new RuntimeException("La page n'existe pas");
        
    };

} catch (Exception $e){
    $mainController->pageErreur($e->getMessage());

}