<?php include("ouvrir_base.php"); 
if(empty($_SESSION['pseudo'])){
	header('Location: index.php');	
	exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	session_destroy();
	header('Location: index.php');	
	exit();
}
?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>
	
<h2>Modifier mon mot de passe :</h2>

<form method="post" action="mon_compte.php">
<label for="password1">Entrer votre mot de passe actuel: </label><input type="password" name="password1" id="password1" placeholder="password" /> <br />
<label for="password2">Entrer votre nouveau mot de passe: </label><input type="password" name="password2" id="password2" placeholder="password" /> <br />
<input type="submit" value="Modifier" />
</form>
<!-- traitement du formulaire d'inscription -->
<?php 
if (!isset($_POST['password1']) || empty($_POST['password2'])){
	//echo "Veuillez entrer votre mot de passe actuel ";
	if (!isset($_POST['password2']) || empty($_POST['password2'])){
		//echo "Veuillez entrer votre nouveau mot de passe";
	}
	echo "<br>";
}
else{
	if (!isset($_POST['password2']) || empty($_POST['password2'])){
		echo "Veuillez entrer votre nouveau mot de passe <br>";
	}
	else{

		// Vérification si le mot de passe ancien est correct
		$test = $bdd->query('SELECT EXISTS (SELECT id_internaute FROM Internaute WHERE mot_de_passe = "' . $_POST['password1'] . '") AS mot_de_passe_correct');
		$correct = $test->fetch();

		// Mot de passe correct
		if ($correct['mot_de_passe_correct']){
			$bdd->exec('UPDATE Internaute SET mot_de_passe = "' . $_POST['password2'] . '" WHERE pseudo = "' . $_SESSION['pseudo'] . '"' );
			echo "<br>Modification effectuée.<br>";
			
			
		}

		// Mot de passe incorrect
		else{
			echo "<br>Ce n'est pas votre mot de passe. Essayez encore.<br>";
		}
	}
}

?>


<h2>Mes recettes :</h2>

<?php 
// On récupère le contenu de la table Recettes_de cuisine
$recettes = $bdd->query('SELECT id_recette, nom_recette FROM Recettes_de_cuisine, Internaute WHERE Recettes_de_cuisine.id_internaute = Internaute.id_internaute AND Internaute.pseudo = \'' . $_SESSION['pseudo'] . '\'');
//On affiche les lignes une à une:
while ($rec = $recettes->fetch()){

echo '<p> <a href="affichage_recette.php?id_recette='.$rec['id_recette'].'">'. $rec['nom_recette'].'</a> </p>';
}
?>		
		
<h2>Mes menus :</h2>

<?php 
// On récupère le contenu de la table Recettes_de cuisine
$menus = $bdd->query('SELECT id_menu, nom_menu FROM Menu, Internaute WHERE Menu.id_internaute = Internaute.id_internaute AND Internaute.pseudo = \'' . $_SESSION['pseudo'] . '\';');
//On affiche les lignes une à une:
while ($rec = $menus->fetch()){

echo '<p> <a href="affichage_menu.php?id_menu='.$rec['id_menu'].'">'. $rec['nom_menu'].'</a> </p>';
}
?>	


<form method="post" action="mon_compte.php">
<input type="submit" value="Déconnexion" />
</form>
<!-- traitement du formulaire de déconnection -->
<?php /*
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	session_destroy();
	header('Location: index.php');	
	exit();
}
*/


?>
	

	
</body>

</html>
