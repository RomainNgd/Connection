<?php

class Security{
     public static function secureHTML($chaine): string{
         return htmlentities($chaine);
     }

     public static function estConnecte(){
         return !empty($_SESSION['profil']);
     }
}