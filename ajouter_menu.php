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
	
<br>


<label for="nom_menu1"><b>Titre du menu: </b></label><input name="nom_menu1" id="nom_menu" type="text" maxlength="255" required="required" value=""/> <br />


<?php 
// On récupère le contenu de la table Categories
$categories = $bdd->query('SELECT nom_categorie FROM Categories');
//On affiche les lignes une à une:
echo '<p>  <label for="recettes">Quelle recette souhaitez-vous ajouter ?</label><br />' ;
echo ' <select name="recettes" id="recettes" onchange="ajouter_recette();"> <option disabled selected>Ajouter une recette</option>' ;

while ($rec = $categories->fetch()){
	
	echo '<optgroup label="' . $rec['nom_categorie'] . '">';
	
	// On récupère les recettes appartenant à chaque catégorie 
	$recettes = $bdd->query('SELECT Recettes_de_cuisine.id_recette AS id_recette,nom_recette FROM Recettes_de_cuisine, Appartenir_catégorie WHERE Recettes_de_cuisine.id_recette = Appartenir_catégorie.id_recette AND Appartenir_catégorie.nom_catégorie = \''. $rec['nom_categorie'] .'\'' );
	//On affiche les lignes une à une:
	while ($rec = $recettes->fetch()){
			echo '<option value="' . $rec['id_recette'] .','.$rec['nom_recette'].'">' . $rec['nom_recette'];
	};   
		
	echo '</optgroup> ';   
	
};      

    echo '</select>   </p>';
	

?>


<!--<input type="submit" value="Ajouter la recette" >-->
	

<p id="recettes_menu">

</p>
	
<form method="post" action="#" id="form" >
	<input type="hidden" name="nom_menu" id="nom_menu_form" value="" required="required" />
	<input type="hidden" name="nb_recettes" id="nb_recettes" value="0" />
</form>
<button onclick="creer_menu()">Créer le menu</button>

<?php

if(isset($_POST['nb_recettes'])){
	if($_POST['nb_recettes'] <= 0){
		echo "Veuillez entrer au moins une recette<br>";
	}
	else{
		$result_id = $bdd->query('SELECT id_internaute FROM Internaute WHERE pseudo = "' . $_SESSION['pseudo'] .'"');
		$id_internaute = $result_id->fetch();
		$id_internaute = $id_internaute['id_internaute'];
		$bdd->exec('INSERT INTO Menu(nom_menu,id_internaute) VALUES("' . $_POST['nom_menu'] .'","'. $id_internaute .'")');
		$menu_id=$bdd->lastInsertId();
		for($i = 1; $i < $_POST['nb_recettes']+1;$i++){
			if(isset($_POST['recette' . $i])){
				$bdd->exec('INSERT INTO Contenir_recette(id_recette,id_menu) VALUES("' . $_POST['recette' . $i] .'","' . $menu_id .'")'); 
				
			}
		}
		echo "Le menu a bien été créé<br>";
	}
	
}

?>







<script>
var nb_recettes = 0;

function creer_menu(){
	document.getElementById("nom_menu_form").value = document.getElementById("nom_menu").value;
	document.getElementById('form').submit();
}

function ajouter_recette(){
	nb_recettes++;
	var value = document.getElementById('recettes').value.split(',');
	document.getElementById('recettes_menu').innerHTML += value[1] +  '<br>';
	document.getElementById('form').innerHTML += "<input type='hidden' name='recette"+nb_recettes+"' value='" + value[1] + "' /> <br>";
	document.getElementById('nb_recettes').value = nb_recettes;
	}


</script>




</body>

</html>
