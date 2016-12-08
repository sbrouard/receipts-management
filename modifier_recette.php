<?php include("ouvrir_base.php"); 
if(!isset($_GET['id_recette']) || !isset($_SESSION['pseudo'])){
	header('Location: index.php');	
	exit();
}
?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); 




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
						 FROM Recettes_de_cuisine AS R1 INNER JOIN Descriptions D ON R1.id_recette = D.id_recette) AS R INNER JOIN Internaute AS I ON R.id_internaute = I.id_internaute
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

<form method="post" action="./modifier_recette?id_recette=<?php echo $_GET['id_recette']; ?>">
	
<label for="nom_recette"><b>Titre de la recette: </b></label><input name="nom_recette" id="nom_recette" type="text" maxlength="255" required="required"
value="<?php echo $rec['nom_recette'];?>"/> <br />

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
	$nb_ingr = -1;
	for($nb_ingr = 1; $ingr=$ingredients->fetch();$nb_ingr++){
		echo '<tr><td><input name="ingredient'. $nb_ingr .'" id="ingredient" type="text" maxlength="255" placeholder="nom de l\'ingrédient" required value="'. $ingr['nom_ingrédient'] .'"/>
		<input name="quantite'. $nb_ingr .'" type="number" min="0" placeholder="quantité" required style="width:80px;"value="'. $ingr['valeur'] .'"/>
		<input name="unite'. $nb_ingr .'" type="text" maxlength="255" placeholder="unité" style="width:80px;" value="'. $ingr['unite'] .'"/></td></tr></table><br>';
	}
?>
	
	
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
				$bdd->exec('INSERT INTO Ingredients(nom_ingredient) Values("' . $_POST['ingredient' . $i] .'")');
			}
			$bdd->exec('INSERT INTO Contenir_ingredients(unite,valeur,id_recette,nom_ingrédient) VALUES("' . $_POST['unite' . $i] . '","' . $_POST['quantite' . $i] . '","' . $recette_id .'", "' . $_POST['ingredient' . $i] .'")'); 
		}
	}
	
	
	// Insérer dans appartenir_categorie
	
	$bdd->exec('INSERT INTO Appartenir_catégorie(nom_catégorie,id_recette) VALUES("' . $_POST['categorie'] .'","' . $recette_id .'")');
	
	
		echo "Recette ajoutée !<br>";
}
else if($_SERVER['REQUEST_METHOD'] == 'POST'){
	echo "Veuillez compléter tous les champs<br>";
}
	
	


	
	
	
?>
	
	
	
	
	
	
	
<!-- Fonction JS pour gérer le nombre d'ingrédients dans la recette -->	
<script>
var nb_ingredients = 1;

function nouvel_ingredient(){
	nb_ingredients++;
	document.getElementById('list_ingredients').innerHTML += '<tr><td><input name="ingredient' + nb_ingredients + '" type="text" maxlength="255" placeholder="nom de l\'ingrédient" required/>  <input name="quantite' + nb_ingredients + '" type="number" min="0" placeholder="quantité" required style="width:80px;"/>  <input name="unite' + nb_ingredients + '" type="text" maxlength="255" placeholder="unité" style="width:80px;" /></td></tr>';
	document.getElementById("nb_ingredients").value = nb_ingredients;
}


</script>

	
</section>
</body>

</html>



