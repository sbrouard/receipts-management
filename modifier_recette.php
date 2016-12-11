<?php include("ouvrir_base.php"); 
if(!isset($_GET['id_recette']) || !isset($_SESSION['pseudo'])){
	header('Location: index.php');	
	exit();
}
?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); 







// traitement du formulaire
$requette_executee = 0;
if(isset($_POST['nom_recette']) && isset($_POST['categorie']) && isset($_POST['nb_personnes']) 
	&& isset($_POST['hpreparation']) && isset($_POST['minpreparation']) && isset($_POST['hcuisson']) && isset($_POST['mincuisson'])
	&& isset($_POST['description']) && isset($_POST['nb_ingredients']) && ($_POST['nb_ingredients'] >= 1)){
	
	$temps_preparation = $_POST['hpreparation'] . ':' . $_POST['minpreparation'] . ':00';
	$temps_cuisson = $_POST['hcuisson'] . ':' . $_POST['mincuisson'] . ':00';
	
// mettre à jour dans Recettes de cuisine
	$bdd->exec('UPDATE Recettes_de_cuisine SET nom_recette = "' . $_POST['nom_recette'] . '",
											   nombre_personnes = "' . $_POST['nb_personnes'] .'",
										       temps_preparation = "' . $temps_preparation .'",
											   temps_cuisson = "' . $temps_cuisson . '"
										   WHERE id_recette = "'. $_GET['id_recette'] .'"');

	
// modifier dans Descriptions

	$bdd->exec('UPDATE Descriptions SET date_fin = CURRENT_DATE WHERE date_fin = "0000-00-00" AND id_recette = "'. $_GET['id_recette'] .'"');
	$bdd->exec('INSERT INTO Descriptions(date_debut,date_fin,texte,id_recette) VALUES(CURRENT_DATE, "0000-00-00","' . $_POST['description'] . '", ' . $_GET['id_recette'] . ')');





	for($i = 1; $i < $_POST['nb_ingredients']+1;$i++){
		
		if(isset($_POST['ingredient' . $i])){
			$test_exists = $bdd->query('SELECT EXISTS (SELECT * FROM Ingredients WHERE nom_ingredient = "' . $_POST['ingredient' . $i] . '") AS ingredient_exists');
			$exists = $test_exists->fetch();
			if(!$exists['ingredient_exists']){ // Si l'ingredient n'est pas deja dans la base, on le rajoute
				$bdd->exec('INSERT INTO Ingredients(nom_ingredient) Values("' . $_POST['ingredient' . $i] .'")');
			}
			
			// inserer dans Contenir Ingredients
			
			//$bdd->exec('INSERT INTO Contenir_ingredients(unite,valeur,id_recette,nom_ingrédient) VALUES("' . $_POST['unite' . $i] . '","' . $_POST['quantite' . $i] . '","' . $recette_id .'", "' . $_POST['ingredient' . $i] .'")'); 
		}
	}
	
	
	// Modifieer dans appartenir_categorie
	
	$bdd->exec('UPDATE Appartenir_catégorie SET nom_catégorie = "'. $_POST['categorie'] .'" WHERE id_recette = "'. $_GET['id_recette'] .'"');
	
		$requete_executee = 1;
}
else if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$requette_executee = -1;
}	
	









// On récupère le contenu de la recette dans la table Recettes_de cuisine
$recettes = $bdd->query('SELECT R.id_recette, nom_recette, 
						DATE_FORMAT(date_ajout, \'%d/%m/%Y\') AS date_ajout_fr, 
						nombre_personnes, 
						DATE_FORMAT(temps_preparation, \'%H %i\') AS temps_prepa, 
						DATE_FORMAT(temps_cuisson, \'%H %i\') AS temps_cuiss, 
						pseudo, texte AS description
						FROM (SELECT R1.id_recette, nom_recette,
								date_ajout, 
								nombre_personnes, 
								temps_preparation,
								temps_cuisson,
								texte,
								id_internaute
						 FROM Recettes_de_cuisine AS R1 INNER JOIN (SELECT * FROM Descriptions WHERE date_fin = "0000-00-00") D ON R1.id_recette = D.id_recette ) AS R INNER JOIN Internaute AS I ON R.id_internaute = I.id_internaute
						WHERE R.id_recette =' . $_GET['id_recette']);
						
// On récupère les informations de la recette						
$rec = $recettes->fetch();

// On récupère les ingrédients et leurs unités
$ingredients = $bdd->query('SELECT *
							FROM Contenir_ingredients 
							WHERE id_recette ='. $_GET['id_recette']);


if ($_SESSION['pseudo'] != $rec['pseudo']){
	header('Location: index.php');	
	exit();
}

?>









<!-- Fomulaire de déclaration d'une recette -->

<body>
<?php include("table_matieres.php");?>
<section id="contenu">	
	
<h2> Modification de la recette</h2>

<form method="post" action="./modifier_recette.php?id_recette=<?php echo $_GET['id_recette']; ?>">
	
<label for="nom_recette"><b>Titre de la recette: </b></label><input name="nom_recette" id="nom_recette" type="text" maxlength="255" required="required"
value="<?php echo $rec['nom_recette'];?>"/> <br />


<?php $categorie = $bdd->query('SELECT nom_catégorie FROM Appartenir_catégorie WHERE id_recette = "'. $_GET['id_recette'] .'"');
$categ = $categorie->fetch();
?>
<label for="categorie"><b>Catégorie de la recette: </b></label>
<select name="categorie" id="categorie">
<?php
$categories = $bdd->query('SELECT * FROM Categories WHERE nom_categorie != \'Autre\'');
while ($cat = $categories->fetch()){
	echo "<option value='" . $cat['nom_categorie']. "'";
	if ($cat['nom_categorie'] == $categ['nom_catégorie']) echo "selected";
	echo ">" . $cat['nom_categorie'] . "</option>";
	}
?>
<option value="NULL">Autre catégorie</option>
</select><br />

<label for="nb_personnes"><b>Recette pour combien de personnes: </b></label><input name="nb_personnes" id="nb_personnes" type="number" min="1" required="required"
value="<?php echo $rec['nombre_personnes'];?>"/> <br />
<label for="temps_preparation"><b>Temps de préparation: </b></label><input name="hpreparation" id="temps_praparation" type="number" min="0" style="width:55px;" required="required" 
value="<?php echo $rec['temps_prepa'][0] , $rec['temps_prepa'][1]; ?>"/>h
<input name="minpreparation" type="number" min=0 max="59" style="width:55px;" required="required"
value="<?php echo $rec['temps_prepa'][3] , $rec['temps_prepa'][4]; ?>"/>min<br />

<label for="temps_cuisson"><b>Temps de cuisson: </b></label><input name="hcuisson" id="temps_cuisson" type="number" min="0" style="width:55px;" required="required"
value="<?php echo $rec['temps_cuiss'][0] , $rec['temps_cuiss'][1]; ?>" />h
<input name="mincuisson" type="number" min=0 max="59" style="width:55px;" required="required"
value="<?php echo $rec['temps_cuiss'][3] , $rec['temps_cuiss'][4]; ?>" />min<br />

<label for="description"><b>Description: </b></label><textarea name="description" id="description" maxlength="255" required>
<?php echo $rec['description']; ?></textarea><br />

<table id="list_ingredients">
	<tr><td><label for="ingredient"><b>Ingrédients: </b></label></td></tr>
<?php	
	$nb_ingr = 0;
	for($nb_ingr = 1; $ingr=$ingredients->fetch();$nb_ingr++){
		echo '<tr id="tr_ingredient'. $nb_ingr .'"><td><input name="ingredient'. $nb_ingr .'" id="ingredient" type="text" maxlength="255" placeholder="nom de l\'ingrédient" required value="'. $ingr['nom_ingrédient'] .'"/>
		<input name="quantite'. $nb_ingr .'" type="number" min="0" placeholder="quantité" required style="width:80px;"value="'. $ingr['valeur'] .'"/>
		<input name="unite'. $nb_ingr .'" type="text" maxlength="255" placeholder="unité" style="width:80px;" value="'. $ingr['unite'] .'"/>
		<img src="./images/icone_supprimer.png" height="30" class="icone_supprimer_ingredient" onclick="supprimer_ingredient(\'tr_ingredient'. $nb_ingr .'\');"/></td></tr></table><br>';
	}
?>
	
	
<input type="hidden" name="nb_ingredients" id="nb_ingredients" required value="1"/>
<span id="ajouter_ingredient" onclick="nouvel_ingredient2();">Ajouter un nouvel ingrédient</span><br /><br />

<input type="submit" value="Modifier" />

</form>
	
	
	
	
<?php

// Affichage du résultat du traitement du formulaire

if(isset($requete_executee) && $requete_executee == 1)
	echo "La recette a été modifiée avec succes<br>";
else if(isset($requete_executee) && $requete_executee == -1)
	echo "Veuillez remplir tous les champs<br>";

?>
	
	
	
	
	
	
	

	
	
	
	
	
	
	
<!-- Fonction JS pour gérer le nombre d'ingrédients dans la recette -->	
<script>
var nb_ingredients = <?php echo $nb_ingr;?> -1;
/*
function nouvel_ingredient(){
	nb_ingredients++;
	document.getElementById('list_ingredients').innerHTML += '<tr><td><input name="ingredient' + nb_ingredients + '" type="text" maxlength="255" placeholder="nom de l\'ingrédient" required/>  <input name="quantite' + nb_ingredients + '" type="number" min="0" placeholder="quantité" required style="width:80px;"/>  <input name="unite' + nb_ingredients + '" type="text" maxlength="255" placeholder="unité" style="width:80px;" /></td></tr>';
	document.getElementById("nb_ingredients").value = nb_ingredients;
}
*/
function nouvel_ingredient2(){
	var new_ingr = document.createElement('tr');
	new_ingr.id = "tr_ingredient" + (nb_ingredients +1);
	console.log(new_ingr.id);
	
	var new_column1 = document.createElement('td');
	
	var new_nom_ingr = document.createElement('input');
	new_nom_ingr.name = "ingredient" + nb_ingredients;
	new_nom_ingr.type = "text";
	new_nom_ingr.max_length = "255";
	new_nom_ingr.placeholder = "nom de l'ingrédient";
	new_nom_ingr.setAttribute("required","required");
	
	new_column1.appendChild(new_nom_ingr);
	
	var new_quantite = document.createElement('input');
	new_quantite.name = "quantite" + nb_ingredients;
	new_quantite.type = "number";
	new_quantite.min = "0";
	new_quantite.placeholder = "quantité";
	new_quantite.style = "width:80px;";
	new_quantite.setAttribute("required","required");
	
	new_column1.appendChild(new_quantite);
	
	var new_unite = document.createElement('input');
	new_unite.name = "unite" + nb_ingredients;
	new_unite.type = "text";
	new_unite.maxlength = "255";
	new_unite.style = "width:80px;";
	
	new_column1.appendChild(new_unite);
	
	var icone = document.createElement('img');
	icone.src = "./images/icone_supprimer.png";
	icone.height = "30";
	icone.className = "icone_supprimer_ingredient";
	icone.id = "img" + nb_ingredients;
	icone.addEventListener("click", function(){ console.log("tr_ingredient" + nb_ingredients);supprimer_ingredient(new_ingr.id);});
	
	new_column1.appendChild(icone);
	
	new_ingr.appendChild(new_column1);
	document.getElementById('list_ingredients').appendChild(new_ingr);
	
	
	nb_ingredients++;
}

function supprimer_ingredient(id_tr_ingredient){	
	var row = document.getElementById(id_tr_ingredient);
    row.parentNode.removeChild(row);

}

</script>

	
</section>
</body>

</html>



