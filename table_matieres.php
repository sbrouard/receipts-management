<header>

<a href="index.php" title="page d'accueil"><h1>Captain Cook</h1></a>

<a href="index.php"><img src="./images/logo.png" title="Captain Cook" /></a>

</header>







<?php 
echo '<nav><ul>';
if(isset($_SESSION['pseudo'])){
	echo 
		'<li></li><a href="liste_recettes.php">Liste des recettes</a> </li>
		<li> <a href="liste_menus.php">Liste des menus</a> </li>
		<li> <a href="ajouter_recette.php"> Ajouter une recette</a> </li>
		<li> <a href="ajouter_menu.php">Ajouter un menu</a></li>
		<li> <a href="statistiques.php">Statistiques</a></li>
		<li> <a href="mon_compte.php">Mon compte</a></li>';
}

else{
	echo 
		'<li> <a href="liste_recettes.php">Liste des recettes</a> </li>
		<li> <a href="liste_menus.php">Liste des menus</a> </li>
		<li> <a href="inscription.php">Inscription</a> </li>
		<li> <a href="identification.php">Identification</a> </li>
		<li> <a href="statistiques.php">Statistiques</a></li>';
}
echo '</ul></nav>';
?>
