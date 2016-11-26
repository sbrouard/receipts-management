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
<label for="nom_recette"><b>Titre de la recette: </b></label><input name="nom_recette" id="nom_recette" type="text" maxlength="255" required="required"/> <br />
<label for="nb_personnes"><b>Recette pour combien de personnes: </b></label><input name="nb_personnes" id="nb_personnes" type="number" min="1" required="required"/> <br />
<label for="temps_preparation">Temps de préparation: </label><input name="hpreparation" id="temps_praparation" type="number" min="0" value="0" style="width:55px;" required="required"/>h
<input name="minpreparation" type="number" min=0 max="59" value="00" style="width:55px;" required="required"/>min<br />
<label for="temps_cuisson">Temps de cuisson: </label><input name="hcuisson" id="temps_cuisson" type="number" min="0" value="0" style="width:55px;" required="required"/>h
<input name="mincuisson" type="number" min=0 max="59" value="00" style="width:55px;" required="required"/>min<br />
<!--<input name="description" type="text" required="required" /><br />-->
<textarea name="description" required></textarea><br />
<table id="list_ingredients">
	<tr><td><label for="ingredient">Ingrédients: </label></td><td><input name="ingredient1" id="ingredient" type="text" required /></td></tr></table><br>


<span onclick="nouvel_ingredient();">Ajouter un nouvel ingrédient</span><br />
<input type="submit">
</form>
<!--<button onclick="nouvel_ingredient();">Ajouter un nouvel ingrédient</button><br />-->
	
	
	
	
	
	
	
	
	
	
<script>
var nb_ingredients = 1;

function nouvel_ingredient(){
	nb_ingredients++;
	document.getElementById('list_ingredients').innerHTML += '<tr><td></td><td><input name="ingredient' + nb_ingredients + '" id="ingredient" type="text" required/></td></tr>';
}


</script>

	
	
</body>

</html>



