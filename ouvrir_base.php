<?php

session_start();

if (isset($_SESSION['pseudo'])) echo $_SESSION['pseudo'];

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=maegrondin;charset=utf8', 'maegrondin', '76zipive');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
	?>
