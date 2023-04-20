<?php
session_start();

define("URL", str_replace("index.php", "", (isset($_SERVER['HTTPS']) ? "https" : "http") .
    "://" . $_SERVER['HTTP_HOST'] . $_SERVER["PHP_SELF"]));

require_once("./Controllers/visiteur/visiteur.controller.php");
require_once("./Controllers/utilisateurs/utilisateurs.controller.php");
require_once("./Controllers/MainController.controller.php");
require_once("./Controllers/administrateur/AdministrateurController.php");
require_once("./Controllers/Security.php");
$mainController = new MainController();
$visiteurController = new VisiteurController();
$utilisateurController = new UtilisateursController();
$administrateurController = new AdministrateurController();

try {
    if (empty($_GET['page'])) {
        $page = "accueil";
    } else {
        $url = explode("/", filter_var($_GET['page'], FILTER_SANITIZE_URL));
        $page = $url[0];
    }

    switch ($page) {
        case "accueil":
            $visiteurController->accueil();
            break;
        case "login":
            $visiteurController->login();
            break;
        case "validation_login":
            if (!empty($_POST['login']) && !empty($_POST['password'])) {
                $login = Security::secureHTML($_POST['login']);
                $password = Security::secureHTML($_POST['password']);
                $utilisateurController->validation_login($login, $password);
            } else {
                Toolbox::ajouterMessageAlerte(
                    "Login ou mot de passe non reseigné",
                    Toolbox::COULEUR_ROUGE
                );
                header('Location:' . URL . "login");
            }
            break;
        case "creerCompte":
            $visiteurController->creerCompte();
            break;
        case 'validationMail':
            $utilisateurController->validationMailCompte($url[1], $url[2]);
            break;
        case "validation_creerCompte":
            if (!empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['mail'])) {
                $login = Security::secureHTML($_POST['login']);
                $password = Security::secureHTML($_POST['password']);
                $mail = Security::secureHTML($_POST['mail']);
                $utilisateurController->validation_creationCompte($login, $password, $mail);
            } else {
                Toolbox::ajouterMessageAlerte("Les 3 informations sont obligatoires !", Toolbox::COULEUR_ROUGE);
                header("Locaton:" . URL . "creerCompte");
            }
            break;
        case "renoyerMailValidation":
            $utilisateurController->renvoyerMailValidation($url[1]);
            break;
        case "vaidationMail":
            echo "test";
            break;
        case "compte":
            if (!Security::estConnecte()) {
                Toolbox::ajouterMessageAlerte("Veuillez vous connecter !", Toolbox::COULEUR_ROUGE);
                header("Location:" . URL . "login");
            } else {
                switch ($url[1]) {
                    case "profil":
                        $utilisateurController->profil();
                        break;
                    case "deconnexion":
                        $utilisateurController->deconnexion();
                        break;
                    case "validation_modificationMail":
                        $utilisateurController->validation_modification(Security::secureHTML($_POST['mail']));
                        break;
                    case "modificationPassword" : $utilisateurController->modificationPassword();
                        break;
                    case "validation_modificationPassword" :
                        if(!empty($_POST['ancienPassword']) && !empty($_POST['nouveauPassword']) && !empty($_POST['confirmationNouveauPassword'])){
                            $ancienPassword = Security::secureHTML($_POST['ancienPassword']);
                            $nouveauPassword = Security::secureHTML($_POST['nouveauPassword']);
                            $confirmationNouveauPassword = Security::secureHTML($_POST['confirmationNouveauPassword']);
                            $utilisateurController->validation_modificationPassword($ancienPassword,$nouveauPassword,$confirmationNouveauPassword);
                        }else{
                            Toolbox::ajouterMessageAlerte("Vous n'avez pas renseigné toutes les informations", Toolbox::COULEUR_ROUGE);
                            header("Location: ".URL."compte/modificationPassword");
                        }
                        break;
                    case "suppresionCompte" : $utilisateurController->suppressionCompte();
                        break;
                    case "validation_modificationImage" :
                        if ($_FILES['image']['size'] > 0){
                            $utilisateurController->validation_modificationImage($_FILES['image']);
                        } else{
                            Toolbox::ajouterMessageAlerte('vous n\'avez pas modifié l\'image', Toolbox::COULEUR_ORANGE);
                            header('Location:'. URL . 'compte/profil');
                        }
                        break;
                    case "administration" :
                        if(!Security::estConnecte()) {
                            Toolbox::ajouterMessageAlerte("Vuillez vous connecter !", Toolbox::COULEUR_ROUGE);
                            header("Location: ".URL."login");
                        } elseif(Security::estAdministrateur()){
                            Toolbox::ajouterMessageAlerte("Vous n'avez pas le droit d'être ici", Toolbox::COULEUR_ROUGE);
                            header("Location: ".URL."accueil");
                        } else {
                            switch($url[1]){
                                case "droits" : $administrateurController->droits();
                                break;
                                default : throw new Exception("La page n'existe pas");
                            }
                        }
                        break;
                        default : throw new Exception("La page n'existe pas");
                }

            };
            break;
    };
} catch (Exception $e) {
    $mainController->pageErreur($e->getMessage());
}
