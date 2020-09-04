<?php
session_start();

session_destroy();
//Nous renvoyer à la page d'accueil apres déconnexion 
header("Location:index.php");