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

    public function pageErreur($msg): void

    {
        parent::pageErreur($msg);
    }

    public function validation_login($login, $password): void
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
                header('Location:' .URL."compte/profil");
            } else {
                $msg = "Le compte".$login ."n'a pas été activé par mail !";
                $msg .= '<a href="renvoyerMailValidation/'.$login.'">Renvoyer le mail de validation<a/>';
                Toolbox::ajouterMessageAlerte (
                    $msg,
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

    public function profil(){
        $datas = $this->UtilisateurManager->getUtilisateurInformation($_SESSION['profil']['login']);
        $_SESSION['profil']['role'] = $datas['role'];
        $data_page = [
            'page_description' => 'page de profil de utilisateur',
            'page_title' => 'page de profil',
            'utilisateur' => $datas,
            'page_javascript' => ['profils.js'],
            'view' => 'Views/utilisateur/profil.view.php',
            'template' => 'Views/partials/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function deconnexion():void{
        Toolbox::ajouterMessageAlerte('la deconnexion a bine été effectué', Toolbox::COULEUR_VERTE);
        unset($_SESSION['profil']);
        header('Location: '.URL.'accueil');
    }

    public function validation_creationCompte($login, $password, $mail):void{
        if ($this->UtilisateurManager->verifLoginDisonible($login)){
            $passwordCrypte = password_hash($password, PASSWORD_DEFAULT);
            $clef = random_int(8, 9999);
            if ($this->UtilisateurManager->bdCreerCompte($login, $passwordCrypte,$mail, $clef)){
                $this->sendEmailValidation($login, $mail, $clef);
                Toolbox::ajouterMessageAlerte('Lec compte a été créer, vérifié votre email', Toolbox::COULEUR_VERTE);
                header("Location:".URL."creerCompte");
            } else{
                Toolbox::ajouterMessageAlerte("Erreur lors de la creation du compte, recommencez !", Toolbox::COULEUR_ROUGE);
                header("Location: ".URL."creerCompte");
            }
        } else {
            Toolbox::ajouterMessageAlerte("le login est utilisé !", Toolbox::COULEUR_ROUGE);
            header("Location: ".URL."creerCompte");
        }
    }

    public function sendEmailValidation($login, $email, $clef){
        $urlVerification = URL . 'validationMail/' . $login . '/' .$clef;
        $sujet = 'email de validation de compte';
        $message = 'pour valider votre mcompte veuillez cliquer sur le lien suivant' . $urlVerification;
        Toolbox::sendMail($sujet, $email, $message);
    }

    public function renvoyerMailValidation($login): void{
        $utilisateur = $this->UtilisateurManager->getUtilisateurInformation($login);
        $this->sendEmailValidation($login, $utilisateur['mail'], $utilisateur['clef']);
        header('Location: '.URL.'login');
    }

    public function validationMailCompte($login,$clef){
        if ($this->UtilisateurManager->bdValidationMailCompte($login, $clef)) {
            Toolbox::ajouterMessageAlerte("le compte a été activé!", Toolbox::COULEUR_VERTE);
            $_SESSION['profil'] = [
                "login" => $login,
            ];
            header("Location: " . URL . 'compte/profil');
        } else {
            Toolbox::ajouterMessageAlerte("Le compte n'a pas été activé!", Toolbox::COULEUR_ROUGE);
            header("Location : " . URL . 'creerCompte');
        }

    }

}