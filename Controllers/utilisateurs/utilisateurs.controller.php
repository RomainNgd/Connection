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

    public function modificationPassword(){
        $datas = $this->UtilisateurManager->getUtilisateurInformation($_SESSION['profil']['login']);
        $_SESSION['profil']['role'] = $datas['role'];
        $data_page = [
            'page_description' => 'page de modficication passwordr',
            'page_title' => 'page de modif pass',
            'utilisateur' => $datas,
            'page_javascript' =>['modificationPassword.js'],
            'view' => 'Views/utilisateur/modificationPassword.view.php',
            'template' => 'Views/partials/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function deconnexion():void{
        Toolbox::ajouterMessageAlerte('la deconnexion a bine été effectué', Toolbox::COULEUR_VERTE);
        unset($_SESSION['profil']);
        header('Location: '.URL.'accueil');
    }

    public function validation_creationCompte($login, $password, $mail):void
    {
        if ($this->UtilisateurManager->verifLoginDisonible($login)){
            $passwordCrypte = password_hash($password, PASSWORD_DEFAULT);
            $clef = random_int(8, 9999);
            if ($this->UtilisateurManager->bdCreerCompte($login, $passwordCrypte,$mail, $clef, "assets/images/profils/profil.png", "user")){
                $this->sendEmailValidation($login, $mail, $clef);
                Toolbox::ajouterMessageAlerte('Lec compte a été créer, vérifié votre email', Toolbox::COULEUR_VERTE);
                header("Location:".URL."login");
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

    /*
        @param $mail
        @return void
    */

    public function validation_modification($mail): void
    {
        if ($this->UtilisateurManager->bdModificationMailUser($_SESSION['profil']['login'],$mail)){
            Toolbox::ajouterMessageAlerte("La modification est effectuée", Toolbox::COULEUR_VERTE);
        } else {
            Toolbox::ajouterMessageAlerte("Acune modification effectuée", Toolbox::COULEUR_ROUGE);
        }
        header("Location:" . URL . 'compte/profil');

    }

    public function validation_modificationPassword($ancienPassword, $nouveauPassword, $confirmationNouveauPassword){
        if ($nouveauPassword === $confirmationNouveauPassword){
            if ($this->UtilisateurManager->isCombinaisonValid($_SESSION['profil']['login'], $ancienPassword)){
                $passwordCrypte = password_hash($nouveauPassword, PASSWORD_DEFAULT);
                if ($this->UtilisateurManager->bdModificationPassword($_SESSION['profil']['login'], $passwordCrypte)){
                    Toolbox::ajouterMessageAlerte('la modification du mot de passe a été effctué', Toolbox::COULEUR_VERTE);
                    header('Location:'.URL."compte/profil");
                } else {
                    Toolbox::ajouterMessageAlerte("la modification a échoué", Toolbox::COULEUR_ROUGE);
                    header('Location:'.URL . 'compte/modificationPassword');
                }
            } else {
                Toolbox::ajouterMessageAlerte("L'ancien mot de passe est incorrecte", Toolbox::COULEUR_ROUGE);
            }
        } else {
            Toolbox::ajouterMessageAlerte("Les password ne correspondent pas", Toolbox::COULEUR_ROUGE);
            header('Location:'.URL . 'compte/modificationPassword');
        }
    }

    public function suppressionCompte():void{
        if ($this->UtilisateurManager->bdSuppressioncompte($_SESSION['profil']['login'])){
            Toolbox::ajouterMessageAlerte("la suppression du compte a été effectué", Toolbox::COULEUR_VERTE);
            $this->deconnexion();
        } else {
            Toolbox::ajouterMessageAlerte('La suppression n\'a pas été effctué', Toolbox::COULEUR_ROUGE);
            header("Location:".URL."compte/profil");
        }
    }

    public function validation_modificationImage($file):void{
        try {
            $repertoire = "public/assets/image/profils/" . $_SESSION['profil']['login']."/";
            $nomImage = Toolbox::ajoutImage($file, $repertoire);
            $ancienneImage = $this->UtilisateurManager->getImageUtilisateur($_SESSION['profil']['login']);
            if ($ancienneImage !== "profils/profil.png"){
                unlink("public/assets/images/".$ancienneImage);
            }
            $nomImageBD = "profils".$_SESSION['profil']['login']."/".$nomImage;
            if ($this->UtilisateurManager->bdAjoutImage($_SESSION['profil']['login'], $nomImageBD)){
                Toolbox::ajouterMessageAlerte('la modification a été effectué', Toolbox::COULEUR_VERTE);
            } else{
                Toolbox::ajouterMessageAlerte('la modification image n\'a pas été effectué', Toolbox::COULEUR_ROUGE);
            }
        } catch (Exception $exception){
            Toolbox::ajouterMessageAlerte($exception->getMessage(), Toolbox::COULEUR_ROUGE);
        }

        header("Location:".URL."compte/profil");
    }
}