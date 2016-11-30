<?php include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>
	
<h3 id="truc">Nombre de recettes créées par catégorie depuis le début de l'année :</h3><br>
	
<?php	

// On récupère les catégories 
$categories = $bdd->query('SELECT C.nom_categorie, COUNT(A.id_recette) AS nb_recettes
							FROM Categories C, Recettes_de_cuisine R, Appartenir_catégorie A
							WHERE A.id_recette = R.id_recette AND A.nom_catégorie = C.nom_categorie 
							GROUP BY C.nom_categorie');
							

// On affiche le tout
while($cat = $categories->fetch()){
	// On affiche les catégories
	echo '<p class="nom_categorie" id="'. $cat['nom_categorie']. '">' .$cat['nom_categorie']. ' : ' .$cat['nb_recettes']. '</p> ';
	echo '</span>';

}
?>

<h3 id="truc">Classement des recettes :</h3><br>

	
<?php	

// On récupère les recettes 
$recettes = $bdd->query('SELECT R.nom_recette, N.valeur
							FROM Recettes_de_cuisine R, Noter N
							WHERE R.id_recette = N.id_recette 
							ORDER BY N.valeur
							DESC');
							

// On affiche le tout
while($rec = $recettes->fetch()){
	// On affiche les recettes triées par note
	echo '<p class="nom_recette" id="'. $rec['nom_recette']. '">' .$rec['nom_recette']. ' : ' .$rec['valeur']. '</p> ';
	echo '</span>';

}
?>

<?php 

if(isset($_SESSION['pseudo'])){
	
	// On récupère les moyennes des notes sur les recettes par menu
	echo '<h3 id="truc">Moyenne des notes sur les recettes de mes menus :</h3><br>';
	$menus = $bdd->query('SELECT M.nom_menu, AVG(N.valeur) AS moyenne
							FROM Menu M, Contenir_recette C, Recettes_de_cuisine R, Noter N
							WHERE R.id_recette = N.id_recette AND M.id_menu = C.id_menu AND R.id_recette = C.id_recette
							GROUP BY M.nom_menu
							ORDER BY moyenne DESC');
							
	// On affiche le tout
	while($me = $menus->fetch()){
		// On affiche les catégories
		echo '<p class="nom_menu" id="'. $me['nom_menu']. '">' .$me['nom_menu']. ' : ' .$me['moyenne']. '</p> ';
		echo '</span>';

}
							
	
}
	
?>
		
</body>
</html>
