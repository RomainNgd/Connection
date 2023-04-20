<?php
class Toolbox {
    public const COULEUR_ROUGE = "alert-danger";
    public const COULEUR_ORANGE = "alert-warning";
    public const COULEUR_VERTE = "alert-success";

    public static function ajouterMessageAlerte($message,$type){
        $_SESSION['alert'][]=[
            "message" => $message,
            "type" => $type
        ];
    }
        public static function sendMail($destinataire, $sujet, $message): void
    {
        $header = "from : mail@gmail.com";
        if (mail($destinataire,$sujet,$message,$header)){
            self::ajouterMessageAlerte(
                "Le mail a bien été envoyé",
                self::COULEUR_VERTE
            );
        }else{
            self::ajouterMessageAlerte(
                "Le mail n'a pas été envoyé",
                self::COULEUR_ROUGE
            );
        }
    }
    public static function ajoutImage($file, $dir): string
{
    if(!isset($file['name']) || empty($file['name'])) {
        throw new RuntimeException("Vous devez indiquer une image");
    }

    if(!file_exists($dir)){
        mkdir($dir, 0777);
    }

    $extention = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
    $random = random_int(0,99999);
    $target_file = $dir. $random ."_".$file['name'];
    if(!getimagesize($file["tmp_name"])) {
        throw new RuntimeException("L'extension du fichier n'est pas reconnu");
    }
    if($extention !== "jpg" && $extention !== "jpeg" && $extention !== "png" && $extention !== "gif") {
        throw new RuntimeException("L'extension du fichier n'est pas reconnu");
    }
    if(file_exists($target_file)) {
        throw new RuntimeException("Le fichier existe déjà");
    }
    if($file['size'] > 5000000) {
        throw new RuntimeException("Le fichier est trop gros");
    }
    if(!move_uploaded_file($file["tmp_name"], $target_file)) {
        throw new RuntimeException("L'ajout de l'image n'a pas fonctionné");
    }else {
        return ($random ."_" . $file['name']);
    }
}
}
