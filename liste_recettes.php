<?php include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>

<h2>Liste des recettes</h2>

<form method="post" action="#">
<label for="categorie">Sélectionner les recettes de la catégorie: </label><select name="categorie" id="categorie">
<option value="toutes" selected>Toutes les gatégories
<?php 
$categories = $bdd->query('SELECT nom_categorie FROM Categories'); 
while($cat = $categories->fetch()){
	echo "<option value='" . $cat['nom_categorie'] ."'>". $cat['nom_categorie'];
}
?>
</select>
<span title="laisser vide pour ignorer">
<label for="nb_personnes">pour: </label><input type="number" name="nb_personnes" id="nb_personnes" min=0 style="width:50px;" /><label for="nb_personnes">personnes </label>
<label for="date">ajoutées après le: </label><input type="date" name="date" id="date" placeholder="jj/mm/aaaa" maxlength="10" style="width:80;"/>
</span>
<input type="submit" value="Sélectionner" />
</form>

<?php 
$from = 'Recettes_de_cuisine R ';
$where = '';
$and = false;
if(isset($_POST['categorie']) && $_POST['categorie'] != 'toutes'){
	$from .= ',Appartenir_catégorie C ';
	$where .= 'WHERE C.nom_catégorie = "' . $_POST['categorie'] . '" AND R.id_recette = C.id_recette ';
	$and =true;
} 

if(isset($_POST['nb_personnes']) && !empty($_POST['nb_personnes'])){
	if($and){
		$where .= 'AND ';
	}
	else{
		$where = 'WHERE ';
		$and = true;
	}
	$where .= 'R.nombre_personnes = '. $_POST['nb_personnes'] .' ';
}

if(isset($_POST['date']) && !empty($_POST['date'])){
	if(!preg_match("#^[0-3]?[0-9]/(0|1)?[0-9]/[0-2][0-9][0-9][0-9]$#",$_POST['date'])){
		echo "La date doit être au format \"jj/mm/aaa\"<br>";
	}
	else{
		$date_sql = explode('/',$_POST['date']);
		if(!checkdate($date_sql[1],$date_sql[0],$date_sql[2])){
			echo "Cette date n'existe pas";
		}
		else{
			if($and){
				$where .= 'AND ';
			}
			else{
				$where = 'WHERE ';
			}
			
			$where .= 'R.date_ajout > "'.$date_sql[2] .'-'. $date_sql[1] .'-'. $date_sql['0'] .'" ';
			
		}
	}
}
	
$requette = 'SELECT R.id_recette,nom_recette FROM '. $from . $where;
$recettes = $bdd->query($requette);

// On récupère le contenu de la table Recettes_de cuisine
//$recettes = $bdd->query('SELECT id_recette, nom_recette FROM Recettes_de_cuisine');
//On affiche les lignes une à une:
while ($rec = $recettes->fetch()){
	echo '<p> <a href="affichage_recette.php?id_recette='.$rec['id_recette'].'">'. $rec['nom_recette'].'</a> </p>';
}

?>







</body>

</html>
