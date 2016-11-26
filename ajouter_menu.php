<?php include("ouvrir_base.php"); 
if(!isset($_SESSION['pseudo'])){
	header('Location: index.php');	
	exit();
}

?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>
	
<form method="post" action="#">
<label for="nom_menu"><b>Titre du menu: </b></label><input name="nom_menu" id="nom_menu" type="text" maxlength="255" required="required"/> <br />


<?php 
// On récupère le contenu de la table Categories
$categories = $bdd->query('SELECT nom_categorie FROM Categories');
//On affiche les lignes une à une:
echo '<p>  <label for="recettes">Quelle recette souhaitez-vous ajouter ?</label><br />' ;
echo ' <select name="recettes" id="recettes">' ;

while ($rec = $categories->fetch()){
	
	echo '<optgroup label="' . $rec['nom_categorie'] . '">';
	
	// On récupère les recettes appartenant à chaque catégorie 
	$recettes = $bdd->query('SELECT nom_recette FROM Recettes_de_cuisine, Appartenir_catégorie WHERE Recettes_de_cuisine.id_recette = Appartenir_catégorie.id_recette AND Appartenir_catégorie.nom_catégorie = \''. $rec['nom_categorie'] .'\'' );
	//On affiche les lignes une à une:
	while ($rec = $recettes->fetch()){
			echo '<option value="recette">' . $rec['nom_recette'] . '</option>';
			
	};   
		
	echo '</optgroup> ';   
	
};      

    echo '</select>   </p>';
	

?>

</form>
<input type="submit" value="Ajouter la recette" >
	
</body>

</html>
