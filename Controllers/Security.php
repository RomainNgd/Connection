<?php

class Security{
     public static function secureHTML($chaine): string{
         return htmlentities($chaine);
     }
}