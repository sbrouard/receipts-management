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

<!DOCTYPE html>

<html>

  <head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="style.css" />
    <title>Recettes de cuisine</title>
  </head>

</html>
