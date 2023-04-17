<?php 

require_once('./Models/MainManager.model.php');

class visiteurManager extends MainManager
{
    public function getUtilisateurs(){
        $req = $this->getBdd()->prepare("SELECT * FORM utilisateur");
        $req->execute();
        $datas = $req->fetchALL(PDO::FETCH_ASSOC);
        $req->closeCursor();
        return $datas;
    }
}