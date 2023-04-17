<?php
require_once ('./Controllers/MainController.controller.php');
require_once ('./Models/visiteur/visiteur.model.php');

class VisiteurController extends MainController{

        private $visiteurManager;


        public function __construct()
        {
            $this->visiteurManager = new VisiteurManager;
        }

        public function accueil()
        {
            $utilisateurs = $this->visiteurManager->getUtilisateurs();

            $data_page = [
                'page_description' => 'page d\'accueil des visiteur',
                'page_title' => 'page de test',
                'utilisateurs' => $utilisateurs,
                'view' => 'Views/accueil.view.php',
                'template' => 'Views/partials/template.php'
            ];
            $this->genererPage($data_page);
        }
         public function login(){

             $data_page = [
                 'page_description' => 'page d\'accueil des visiteur',
                 'page_title' => 'page de login',
                 'view' => 'Views/visiteurs/connection.view.php',
                 'template' => 'Views/partials/template.php'
             ];
             $this->genererPage($data_page);
         }

}