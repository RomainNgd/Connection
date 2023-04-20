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
}
