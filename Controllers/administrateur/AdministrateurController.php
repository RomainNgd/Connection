<?php
require_once './Models/administrateur/administrateur.model.php';
require_once './Controllers/MainController.controller.php';
class AdministrateurController extends MainController
{
    private $administrateurManager;

    public function __construct(){
        $this->administrateurManager = new AdminitrateurManager();
    }

    public function droits(){
        $utilisateur = $this->administrateurManager->getUtilisateurs();
        $data_page = [
            'page_description' => 'page de droit',
            'page_title' => 'page de droit',
            'utilisateurs' => $utilisateur,
            'page_javascript' => ['profils.js'],
            'view' => 'Views/Administrateur/droits.view.php',
            'template' => 'Views/partials/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function pageErreur($msg):void{
        parent::pageErreur($msg);
    }
}