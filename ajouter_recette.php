<?php include("ouvrir_base.php"); 
if(!isset($_SESSION['pseudo'])){
	header('Location: index.php');	
	exit();

}
?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>



<!-- Fomulaire de déclaration d'une recette -->

<body>
	
<form method="post" action="#">
	
<label for="nom_recette"><b>Titre de la recette: </b></label><input name="nom_recette" id="nom_recette" type="text" maxlength="255" required="required"/> <br />

<label for="categorie"><b>Catégorie de la recette: </b></label>
<select name="categorie" id="categorie">
<?php
$categories = $bdd->query('SELECT * FROM Categories WHERE nom_categorie != \'Autre\'');
while ($cat = $categories->fetch()){
	echo "<option value='" . $cat['nom_categorie']. "'>" . $cat['nom_categorie']. "</option>";
	}
?>
<option value="NULL">Autre catégorie</option>
</select><br />

<label for="nb_personnes"><b>Recette pour combien de personnes: </b></label><input name="nb_personnes" id="nb_personnes" type="number" min="1" required="required"/> <br />
<label for="temps_preparation"><b>Temps de préparation: </b></label><input name="hpreparation" id="temps_praparation" type="number" min="0" value="0" style="width:55px;" required="required"/>h
<input name="minpreparation" type="number" min=0 max="59" value="00" style="width:55px;" required="required"/>min<br />

<label for="temps_cuisson"><b>Temps de cuisson: </b></label><input name="hcuisson" id="temps_cuisson" type="number" min="0" value="0" style="width:55px;" required="required"/>h
<input name="mincuisson" type="number" min=0 max="59" value="00" style="width:55px;" required="required"/>min<br />

<label for="description"><b>Description: </b></label><textarea name="description" id="description" maxlength="255" required></textarea><br />

<table id="list_ingredients">
	<tr><td><label for="ingredient"><b>Ingrédients: </b></label></td><td><input name="ingredient1" id="ingredient" type="text" maxlength="255" placeholder="nom de l'ingrédient" required />
																		<input name="quantite1" type="number" min="0" placeholder="quantité" required style="width:80px;"/>
																		<input name="unite1" type="text" maxlength="255" placeholder="unité" style="width:80px;" /></td></tr></table><br>
<input type="hidden" name="nb_ingredients" id="nb_ingredients" required value="1"/>
<span onclick="nouvel_ingredient();">Ajouter un nouvel ingrédient</span><br />

<input type="submit">

</form>
	
	
	
	
	
<?php
// ajout des recettes dans la base de donnée
if(isset($_POST['nom_recette']) && isset($_POST['categorie']) && isset($_POST['nb_personnes']) 
	&& isset($_POST['hpreparation']) && isset($_POST['hcuisson']) && isset($_POST['mincuisson'])
	&& isset($_POST['description']) && isset($_POST['nb_ingredients']) && ($_POST['nb_ingredients'] >= 1)){
	
	$temps_preparation = $_POST['hpreparation'] . ':' . $_POST['minpreparation'] . ':00';
	$temps_cuisson = $_POST['hcuisson'] . ':' . $_POST['mincuisson'] . ':00';
	
// inserer dans Recettes de cuisine
	$bdd->exec('INSERT INTO Recettes_de_cuisine(nom_recette,date_ajout,nombre_personnes,temps_preparation,temps_cuisson,id_internaute) 
										VALUES("' . $_POST['nom_recette'] . '",
												CURRENT_DATE, "' . $_POST['nb_personnes'] .'",
												"' . $temps_preparation .'","' . $temps_cuisson . '",
												(SELECT id_internaute FROM Internaute WHERE pseudo="' . $_SESSION['pseudo'] .'"))');
	
	//$recette_id =$bdd->query('SELECT LAST_INSERT_ID()');
	$recette_id=$bdd->lastInsertId();
// inserer dans Descriptions

	$bdd->exec('INSERT INTO Descriptions(date_debut,date_fin,texte,id_recette) VALUES(CURRENT_DATE, "0000-00-00","' . $_POST['description'] . '", 
	' . $recette_id . ')');

// inserer dans ingredients si abstent

// inserer dans Contenir Ingredients

	for($i = 1; $i < $_POST['nb_ingredients']+1;$i++){
		
		if(isset($_POST['ingredient' . $i])){
			$test_exists = $bdd->query('SELECT EXISTS (SELECT * FROM Ingredients WHERE nom_ingredient = "' . $_POST['ingredient' . $i] . '") AS ingredient_exists');
			$exists = $test_exists->fetch();
			if(!$exists['ingredient_exists']){ // Si l'ingredient n'est pas deja dans la base, on le rajoute
				$bdd->exec('INSERT INTO Ingredients(nom_ingredient) Values(LOWER("' . $_POST['ingredient' . $i] .'"))');
			}
			$bdd->exec('INSERT INTO Contenir_ingredients(unite,valeur,id_recette,nom_ingrédient) VALUES("' . $_POST['unite' . $i] . '","' . $_POST['quantite' . $i] . '","' . $recette_id .'", LOWER("' . $_POST['ingredient' . $i] .'"))'); 
		}
	}
	
	
	// Insérer dans appartenir_categorie
	
	$bdd->exec('INSERT INTO Appartenir_catégorie(nom_catégorie,id_recette) VALUES("' . $_POST['categorie'] .'","' . $recette_id .'")');
	
	
		echo "Recette ajoutée !<br>";
	}
	else{
		echo "Veuillez compléter tous les champs<br>";
	}
	
	


	
	
	
?>
	
	
	
	
	
	
	
<!-- Fonction JS pour gérer le nombre d'ingrédients dans la recette -->	
<script>
var nb_ingredients = 1;

function nouvel_ingredient(){
	nb_ingredients++;
	document.getElementById('list_ingredients').innerHTML += '<tr><td></td><td><input name="ingredient' + nb_ingredients + '" type="text" maxlength="255" placeholder="nom de l\'ingrédient" required/>  <input name="quantite' + nb_ingredients + '" type="number" min="0" placeholder="quantité" required style="width:80px;"/>  <input name="unite' + nb_ingredients + '" type="text" maxlength="255" placeholder="unité" style="width:80px;" /></td></tr>';
	document.getElementById("nb_ingredients").value = nb_ingredients;
}


</script>

	
	
</body>

</html>



