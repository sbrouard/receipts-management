<?php

session_start();

if (isset($_SESSION['pseudo'])) echo $_SESSION['pseudo'];

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=recettes;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
	?>
