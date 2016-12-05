<?php include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>

<?php include("table_matieres.php"); ?>
<section id="contenu">

<h2>Liste des menus</h2>


<form method="post" action="#">
<label for="date">Sélectionner uniquement les menus ne comportant que des recettes ajoutées après le: </label><input type="date" name="date" id="date" placeholder="jj/mm/aaaa" maxlength="10" style="width:80;"/>
<input type="submit" value="sélectionner" />
</form>


<form method="post" action="#">
Sélectionner uniquement les menus ne comportant que des recettes avec des ingrédients peu caloriques: kcal&lt;
<input type="number" name ="kcal_max" id="kcal_max" min="0" required style="width:70px;">kcal
<input type="submit" value="sélectionner" />
</form>


<?php 

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
			//echo 'SELECT id_menu,nom_menu FROM Menu WHERE id_menu NOT IN (SELECT M.id_menu FROM Menu M,Contenir_recette C, Recettes_de_cuisine R WHERE R.date_ajout < "' . $date_sql[2] .'-'. $date_sql[1] .'-'. $date_sql['0'] .'" AND R.id_recette = C.id_recette AND C.id_menu= M.id_menu)';
			$menus = $bdd->query('SELECT id_menu,nom_menu FROM Menu WHERE id_menu NOT IN (SELECT M.id_menu FROM Menu M,Contenir_recette C, Recettes_de_cuisine R WHERE R.date_ajout < "' . $date_sql[2] .'-'. $date_sql[1] .'-'. $date_sql['0'] .'" AND R.id_recette = C.id_recette AND C.id_menu= M.id_menu)');
			/*$test = $bdd->query('SELECT M.id_menu FROM Menu M,Contenir_recette C, Recettes_de_cuisine R WHERE R.date_ajout < "' . $date_sql[2] .'-'. $date_sql[1] .'-'. $date_sql['0'] .'" AND R.id_recette = C.id_recette AND C.id_menu= M.id_menu');
			while($t = $test->fetch()){
				echo $t['id_menu'].'<br>';
			}*/
			while ($men = $menus->fetch()){
				echo '<p> <a href="affichage_menu.php?id_menu='.$men['id_menu'].'">'. $men['nom_menu'].'</a> </p>';
			}
		}
	}
}

if(isset($_POST['kcal_max']) && !empty($_POST['kcal_max'])){
	$menus_peu_cal = $bdd->query('SELECT DISTINCT Contenir_recette.id_menu, Menu.nom_menu 
								FROM Contenir_recette INNER JOIN Menu
								ON Contenir_recette.id_menu = Menu.id_menu 
								WHERE Contenir_recette.id_menu NOT IN
								(SELECT id_menu FROM Contenir_recette 
								WHERE id_recette IN(SELECT Recettes_de_cuisine.id_recette 
								FROM Recettes_de_cuisine INNER JOIN Contenir_ingredients
								ON Recettes_de_cuisine.id_recette=Contenir_ingredients.id_recette
								WHERE nom_ingrédient IN (SELECT nom_ingredient FROM Ingredients 
								WHERE nom_ingredient NOT IN (SELECT nom_ingredient FROM Avoir_Caracteristiques WHERE nom_caracteristique="Calories") 
								OR nom_ingredient NOT IN (SELECT nom_ingredient FROM Avoir_Caracteristiques WHERE nom_caracteristique="Calories" AND valeur<'.$_POST['kcal_max'].'))))');
	 
	while ($menu = $menus_peu_cal->fetch()){
		echo '<p> <a href="affichage_menu.php?id_menu='.$menu['id_menu'].'">'. $menu['nom_menu'].'</a> </p>';
	}
}

else{
	$menus = $bdd->query('SELECT id_menu,nom_menu FROM Menu');
	while ($men = $menus->fetch()){
		echo '<p> <a href="affichage_menu.php?id_menu='.$men['id_menu'].'">'. $men['nom_menu'].'</a> </p>';
	}
}

?>

</section>
</body>

</html>
