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

    public function verifLoginDisonible($login): bool
    {
        $utilisateur = $this->getUtilisateurInformation($login);
        return empty($utilisateur);
    }
    public function bdCreerCompte($login, $passwordCrypte, $mail, $clef): bool{
        $req = "INSERT INTO user (login, password, mail, is_valid, role, clef, image) VALUES (:login, :password, :mail, 0, 'user', :clef, '')";
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

    /*    
   *  @param $login
   *  @param $clef
   *  @return bool
*/
public function bdValidationMailCompte($login,$clef): bool
{
    $req = "UPDATE user set is_valid = 1 WHERE login = :login and clef = :clef";
    $stmt = $this->getBdd()->prepare($req);
    $stmt->bindValue(":login", $login, PDO::PARAM_STR);
    $stmt->bindValue(':clef', $clef, PDO::PARAM_INT);
    $stmt->execute();
    $estModifier = ($stmt->rowCount() > 0 );
    $stmt->closeCursor();
    return $estModifier;
}
    public function bdModificationMailUser($login, $mail): bool
    {
        $req = "UPDATE user set mail = :mail WHERE login = :login";
        $stmt = $this->getBdd()->prepare($req);
        $stmt->bindValue(":login", $login, PDO::PARAM_STR);
        $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
        $stmt->execute();
        $estModifier = ($stmt->rowCount() > 0 );
        $stmt->closeCursor();
        return $estModifier;
    }
}