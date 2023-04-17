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
        $req = 'SELECT is_valid FROM user WHERE login = :login';
        $prep = $this->getBdd()->prepare($req);
        $prep->bindValue(':login', $login, PDO::PARAM_STR);
        $prep->execute();
        $res = $prep->fetch(PDO::FETCH_ASSOC);
        $prep->closeCursor();
        return $res['is_valid'];
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

    public function bdCreerCompte($login, $passwordCrypte, $mail, $clef): bool{
        $req = "INSERT INTO utilisateur (login, password, mail, est_valid, role, clef, image) VALUES (:login, :password, :mail, 0, 'user', :clef, '')";
        $stmt = $this->getBdd()->prepare($req);
        $stmt->bindValue(":login", $login, PDO::PARAM_STR);
        $stmt->bindValue(':password', $passwordCrypte, PDO::PARAM_STR);
        $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
        $stmt->bindValue(':clef', $clef, PDO::PARAM_INT);
        $stmt->execute();
        $estModifier = ($stmt->rowCount() > 0 );
        $stmt->closeCursor();
        return $estModifier;

    }
}