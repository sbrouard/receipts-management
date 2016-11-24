<?php include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>


<h2>Inscription</h2>

// Formulaire d'inscription
<form method="post" action=".">
<label for="pseudo">Enter votre pseudo: </label><input type="text" name="pseudo" id="pseudo" placeholder="pseudo" /> <br />
<input type="submit" value="M'inscrire" />
</form>


// traitement du formulaire d'inscription
<?php 
if (isset($_POST['pseudo'])){ // si on recoit un formulaire complété

	// Vérification de l'unicité du pseudo 
	$test = $bdd->query('SELECT EXISTS (SELECT id_internaute FROM Internaute WHERE pseudo = "' . $_POST['pseudo'] . '") AS pseudo_exists');
	$exists = $test->fetch();

	// Si le pseudo n'est pas disponible
	if ($exists['pseudo_exists']){ 
		echo "Ce pseudo n'est pas disponible.<br>";
	}

	// Si le pseudo est disponible
	else{
		$bdd->exec('INSERT INTO Internaute(pseudo) VALUES($_POST['pseudo']));
		echo "Inscription validée";
	}
}
?>



</body>

</html>
