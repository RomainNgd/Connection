<?php
require_once ('./Controllers/MainController.controller.php');

class VisiteurController extends MainController{

        private $visiteurManager;


        public function __construct()
        {
            parent::__construct();
            $this->visiteurManager = new VisiteurManager;
        }

    public function getUser(){
            $users = $this->visiteurManager->getUtilisateurs();

            $data_page = [
                'page_description' => 'page d\'accueil des visiteur',
                'page_title' => 'page de test',
                'utilisateurs'=> $users,
                'view'=> 'Views/Visiteurs/accueil.php',
                'template' => 'Views/partials/template.php'
            ];
            $this->genererPage($data_page);
        }
}