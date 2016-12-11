<?php

session_start();

if (isset($_SESSION['pseudo'])) echo $_SESSION['pseudo'];

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=maegrondin;charset=utf8', 'maegrondin', '76zipive', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
	?>
