<?php
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=maegrondin;charset=utf8', 'maegrondin', '76zipive');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
	?>