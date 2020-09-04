<?php

try
{
    $db = new PDO('mysql:host=localhost; dbname=projet_bdd; charset=utf8', 'root', 'Kalikondo98', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}

function hashPassword( $pwd )
{
    return sha1('e*?g^*~Ga7' . $pwd . '9!cF;.!Y)?');
}

function redimmension($photo, $dossier, $nom)
{
    if ((strtolower(strrchr($photo, '.')) === ".jpg") OR (strtolower(strrchr($photo, '.')) === ".jpeg")) {
        # code...
        $source = imagecreatefromjpeg($photo); 
        $destination = imagecreatetruecolor(200, 150); 
        $largeur_source = imagesx($source);
        $hauteur_source = imagesy($source);
        $largeur_destination = imagesx($destination);
        $hauteur_destination = imagesy($destination);

        imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);

        imagejpeg($destination, $dossier.'/'.$nom);
    }
    else
    {
        $source = imagecreatefrompng($photo); 
        $destination = imagecreatetruecolor(200, 150); 
        $largeur_source = imagesx($source);
        $hauteur_source = imagesy($source);
        $largeur_destination = imagesx($destination);
        $hauteur_destination = imagesy($destination);

        imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);

        imagepng($destination, $dossier.'/'.$nom);
    }
    
}

function redimmension2($photo, $dossier, $nom)
{
    if ((strtolower(strrchr($photo, '.')) === ".jpg") OR (strtolower(strrchr($photo, '.')) === ".jpeg")) {
        # code...
        $source = imagecreatefromjpeg($photo); 
        $destination = imagecreatetruecolor(400, 500); 
        $largeur_source = imagesx($source);
        $hauteur_source = imagesy($source);
        $largeur_destination = imagesx($destination);
        $hauteur_destination = imagesy($destination);

        imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);

        imagejpeg($destination, $dossier.'/'.$nom);
    }
    else
    {
        $source = imagecreatefrompng($photo); 
        $destination = imagecreatetruecolor(400, 500); 
        $largeur_source = imagesx($source);
        $hauteur_source = imagesy($source);
        $largeur_destination = imagesx($destination);
        $hauteur_destination = imagesy($destination);

        imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);

        imagepng($destination, $dossier.'/'.$nom);
    }
    
}

