<?php include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>

<?php include("table_matieres.php"); ?>
<section id="contenu">

<h2>Inscription</h2>

<!-- Formulaire d'inscription -->
<form method="post" action="inscription.php">
<label for="pseudo">Enter votre pseudo: </label><input type="text" name="pseudo" id="pseudo" placeholder="pseudo" required="required"/> <br />
<label for="password">Entrer votre mot de passe: </label><input type="password" name="password" id="password" placeholder="password" required="required"/> <br />
<input type="submit" value="M'inscrire" />
</form>





<!-- traitement du formulaire d'inscription -->
<?php 
if (!isset($_POST['pseudo']) || empty($_POST['pseudo'])){
	echo "Veuillez entrer un pseudo ";
	if (!isset($_POST['password']) || empty($_POST['password'])){
		echo "et un mot de passe";
	}
	echo "<br>";
}
else{
	if (!isset($_POST['password']) || empty($_POST['password'])){
		echo "Veuillez entrer un mot de passe <br>";
	}
	else{

		// Vérification de l'unicité du pseudo 
		$test = $bdd->query('SELECT EXISTS (SELECT id_internaute FROM Internaute WHERE pseudo = "' . $_POST['pseudo'] . '") AS pseudo_exists');
		$exists = $test->fetch();

		// Si le pseudo n'est pas disponible
		if ($exists['pseudo_exists']){ 
			echo "<br>Ce pseudo n'est pas disponible.<br>";
		}

		// Si le pseudo est disponible
		else{
			$bdd->exec('INSERT INTO Internaute(pseudo,mot_de_passe) VALUES("'. $_POST['pseudo'] . '","' . $_POST['password'] . '")');
			echo "<br>Inscription validée.<br>";
		}
	}
}

?>

</section>	

</body>

</html>
