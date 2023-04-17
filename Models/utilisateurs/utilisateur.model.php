<?php

class UtilisateurManager extends MainManager {

    public function isCombinaisonValid($login,$password){
        $passwordDB = $this->getPasswordUser($login);
        return password_verify($password, $passwordDB);
    }

    public function getPasswordUser($login){
        $req = 'SELECT password FROM user WHERE login = :login';
        $prep = $this->getBdd()->prepare($req);
        $prep->bindValue(':login', $login, PDO::PARAM_STR);
        $prep->execute();
        $res = $prep->fetch(PDO::FETCH_ASSOC);
        $prep->closeCursor();
        return $res['password'];
    }

    public function compteEstActive($login): bool{
        $req = 'SELECT is_active FROM user WHERE login = :login';
        $prep = $this->getBdd()->prepare($req);
        $prep->bindValue(':login', $login, PDO::PARAM_STR);
        $prep->execute();
        $res = $prep->fetch(PDO::FETCH_ASSOC);
        $prep->closeCursor();
        return $res['is_active'];
    }

    public function getUtilisateurInformation($login){
        $req = 'SELECT * FROM user WHERE login = :login';
        $prep = $this->getBdd()->prepare($req);
        $prep->bindValue(':login', $login, PDO::PARAM_STR);
        $prep->execute();
        $res = $prep->fetch(PDO::FETCH_ASSOC);
        $prep->closeCursor();
        return $res;
    }
}