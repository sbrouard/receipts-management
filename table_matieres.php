<?php 

if(isset($_SESSION['pseudo'])){
	echo 
		'<p> <a href="liste_recettes.php">Liste des recettes</a> </p>
		<p> <a href="liste_menus.php">Liste des menus</a> </p>
		<p> <a href="ajouter_recette.php"> Ajouter une recette</a> </p>
		<p> <a href="ajouter_menu.php">Ajouter un menu</a></p>
		<p> <a href="mon_compte.php">Mon compte</a></p>';
}

else{
	echo 
		'<p> <a href="liste_recettes.php">Liste des recettes</a> </p>
		<p> <a href="liste_menus.php">Liste des menus</a> </p>
		<p> <a href="inscription.php">Inscription</a> </p>
		<p> <a href="identification.php">Identification</a> </p>';
}

?>
