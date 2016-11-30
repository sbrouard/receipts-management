<?php include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>
	
<?php	
// On récupère le contenu du menu dans la table Menu
$menus = $bdd->query('SELECT M.id_menu, M.nom_menu, I.pseudo
						FROM Menu AS M INNER JOIN Internaute AS I ON M.id_internaute = I.id_internaute
						WHERE id_menu =' . $_GET['id_menu']);
						
// On récupère les informations du menu					
$me = $menus->fetch();
							

// On récupère les catégories du menu
$catégories = $bdd->query('SELECT DISTINCT nom_catégorie
							FROM Recettes_de_cuisine AS R, Appartenir_catégorie AS A, Contenir_recette AS C
							WHERE R.id_recette = C.id_recette AND R.id_recette = A.id_recette AND C.id_menu ='. $_GET['id_menu']);
							

// Affichage du menu
echo '<h3 id="truc">'. $me['nom_menu'].' (Ajouté par '. $me['pseudo'] . ')</h3><br>';
while($cat = $catégories->fetch()){
	// On affiche les catégories
	echo '<u class="nom_catégorie" id="'. $cat['nom_catégorie']. '">' .$cat['nom_catégorie']. ': </u> ';
	
	//On récupère les recettes de la catégorie
	$recettes = $bdd->query('SELECT R.id_recette, R.nom_recette
							FROM Recettes_de_cuisine AS R, Contenir_recette AS C, Appartenir_catégorie AS A 
							WHERE R.id_recette = C.id_recette AND R.id_recette = A.id_recette AND A.nom_catégorie = "'. $cat['nom_catégorie']. '"AND C.id_menu ='. $_GET['id_menu']);
	
	// On les affiche 
	while($rec = $recettes->fetch()){
		echo '<p> <a href="affichage_recette.php?id_recette='.$rec['id_recette'].'">'. $rec['nom_recette'].'</a> </p>';
	}
	echo '</span>';

}

	
		
?>
	
</body>
</html>
