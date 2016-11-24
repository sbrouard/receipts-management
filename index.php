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

<body>
<h1>Recettes de cuisine</h1>


<h2>Liste des recettes (test)</h2>

<?php 
// On récupère le contenu de la table Recettes_de cuisine
$recettes = $bdd->query('SELECT * FROM Recettes_de_cuisine');
//On affiche les lignes une à une:
while ($rec = $recettes->fetch()){
echo '<b>' . $rec['nom_recette'] .'</b> Temps de préparation: ' . $rec['temps_preparation'] . '  Temps de cuisson: ' . $rec['temps_cuisson'] .'<br>';
}

?>




<h2>Inscription</h2>


<form method="post" action=".">
<label for="pseudo">Enter votre pseudo: </label><input type="text" name="pseudo" id="pseudo" placeholder="pseudo" /> <br />
<input type="submit" value="M'inscrire" />
</form>
<?php 
// traitement du formulaire d'inscription
if (isset($_POST['pseudo'])){ // si on recoit un formulaire complété
	// Vérification de l'unicité du pseudo 
	$test = $bdd->query('SELECT EXISTS (SELECT id_internaute FROM Internaute WHERE pseudo = "' . $_POST['pseudo'] . '") AS pseudo_exists');
	$exists = $test->fetch();
	if ($exists['pseudo_exists']){ 
		echo "Ce pseudo n'est pas disponible.<br>";
	}
	else{
		// faire la requete INSERT INTO + affiché inscription validée
	}
}
?>

</body>

</html>
