<?php 

include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>


<h2>Identification</h2>


<form method="post" action="identification.php">
<label for="pseudo">Enter votre pseudo: </label><input type="text" name="pseudo" id="pseudo" placeholder="pseudo" required="required"/> <br />
<label for="password">Entrer votre mot de passe: </label><input type="password" name="password" id="password" placeholder="password" required="required"/> <br />
<input type="submit" value="M'identifier" />
</form>


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
		echo "Veuillez entrer un mot de passe<br>";
	}else{
	
		// Vérification de l'existance du pseudo avec correspo,dance du mot de passe
		$test = $bdd->query('SELECT EXISTS (SELECT id_internaute FROM Internaute WHERE pseudo = "' . $_POST['pseudo'] . '" AND mot_de_passe = "' . $_POST['password'] .'") AS pseudo_exists');
		$exists = $test->fetch();

		// Si le pseudo existe
		if ($exists['pseudo_exists']){ 
			$_SESSION['pseudo'] = $_POST['pseudo'];
			header('Location: index.php');
			  exit();
		}

		// Si le pseudo n'existe pas
		else{
			echo "Pseudo ou mot de passe incorrect";
		}
	}
}
?>



</body>

</html>
