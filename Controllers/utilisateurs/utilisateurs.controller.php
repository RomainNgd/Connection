<?php
require_once('./Controllers/MainController.controller.php');
require_once('./Models/utilisateurs/utilisateur.model.php');
class UtilisateursController extends MainController

{

    private $UtilisateurManager; 

    public function __construct()
    {
        $this->UtilisateurManager=new UtilisateurManager();
    }

       public function validation_login($login, $password): void

    {
        echo "test";
    }

    public function pageErreur($msg): void

    {
        parent::pageErreur($msg);
    }

    public function validtion_login($login, $password): void
    {
        if($this->UtilisateurManager->isCombinaisonValid($login, $password)){
            if($this->UtilisateurManager->compteEstActive($login)){
                Toolbox::ajouterMessageAlerte(
                    "Bon retour parmi nous !",
                    Toolbox::COULEUR_VERTE
                );
                $_SESSION['profil'] = [
                    'login' => $login
                ];
                header('Location :' .URL."compte/profil");
            } else {
                Toolbox::ajouterMessageAlerte (
                    "Le compte n'a pas été activé par mail !",
                    Toolbox::COULEUR_ROUGE
                );
                header('location'.URL."login");
            }
        }else{
            Toolbox::ajouterMessageAlerte(
                "Combinaison login /mot de passe valide !",
                Toolbox::COULEUR_ROUGE
            );
                header('Location:'.URL."login");
        }
    }

}