<?php
session_start();

define("URL", str_replace("index.php", "", (isset($_SERVER['HTTPS']) ? "https" : "http") .
    "://" . $_SERVER['HTTP_HOST'] . $_SERVER["PHP_SELF"]));

require_once("./Controllers/visiteur/visiteur.controller.php");
require_once("./Controllers/utilisateurs/utilisateurs.controller.php");
require_once("./Controllers/MainController.controller.php");
require_once("./Controllers/Security.php");
$mainController = new MainController();
$visiteurController = new VisiteurController();
$utilisateurController = new UtilisateursController();

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
                        var_dump(Security::secureHTML($_POST['mail']));
                        $utilisateurController->validation_modification(Security::secureHTML($_POST['mail']));
                        break;
                    case "modification_Password" : $utilisateurController->modificationPassword();
                    default:
                        throw new RuntimeException("La page n'existe pas");
                }
            };
            break;
    };
} catch (Exception $e) {
    $mainController->pageErreur($e->getMessage());
}
