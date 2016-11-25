<?php include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>
	
<?php	
// On récupère le contenu de la recette dans la table Recettes_de cuisine
$recettes = $bdd->query('SELECT R.id_recette, nom_recette, 
						DATE_FORMAT(date_ajout, \'%d/%m/%Y\') AS date_ajout_fr, 
						nombre_personnes, 
						DATE_FORMAT(temps_preparation, \'%Hh %imin %ss\') AS temps_prepa, 
						DATE_FORMAT(temps_cuisson, \'%Hh %imin %ss\') AS temps_cuiss, 
						pseudo
						FROM Recettes_de_cuisine AS R INNER JOIN Internaute AS I ON R.id_internaute = I.id_internaute
						WHERE R.id_recette =' . $_GET['id_recette']);
						
// On récupère les informations de la recette						
$rec = $recettes->fetch();

// On récupère les ingrédients et leurs unités
$ingredients = $bdd->query('SELECT *
							FROM Contenir_ingredients 
							WHERE id_recette ='. $_GET['id_recette']);


// Affichage de la recette
echo '<h3>'. $rec['nom_recette'].' (Ajoutée le ' . $rec['date_ajout_fr'] . ' par '. $rec['pseudo'] . ')</h3><br>';
echo 'Nombre de personnes : '. $rec['nombre_personnes']. '<br>';
echo 'Temps de préparation : '. $rec['temps_prepa']. '<br>';
echo 'Temps de cuisson : '. $rec['temps_cuiss']. '<br>';
echo 'Ingrédients nécessaires : ';
while($ing = $ingredients->fetch()){
	echo $ing['valeur'].' '.$ing['unite']. ' de ' .$ing['nom_ingrédient'] . ', ';
}
echo '<br>';
?>


</body>
</html>
