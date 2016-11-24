<?php include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>


<h2>Identification</h2>

// Formulaire d'identification
<form method="post" action="identification.php">
<label for="pseudo">Enter votre pseudo: </label><input type="text" name="pseudo" id="pseudo" placeholder="pseudo" /> <br />
<input type="submit" value="M'identifier" />
</form>

// traitement du formulaire d'inscription
<?php 
if (isset($_POST['pseudo'])){ // si on recoit un formulaire complété

	// Vérification de l'unicité du pseudo 
	$test = $bdd->query('SELECT EXISTS (SELECT id_internaute FROM Internaute WHERE pseudo = "' . $_POST['pseudo'] . '") AS pseudo_exists');
	$exists = $test->fetch();

	// Si le pseudo existe
	if ($exists['pseudo_exists']){ 
		header('Location: page_accueil.php');
 		  exit();
	}

	// Si le pseudo n'existe pas
	else{
		echo "Pseudo incorrect";
	}
}
?>



</body>

</html>
