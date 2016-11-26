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
						DATE_FORMAT(temps_preparation, \'%Hh %imin\') AS temps_prepa, 
						DATE_FORMAT(temps_cuisson, \'%Hh %imin\') AS temps_cuiss, 
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
echo '<h3 id="truc">'. $rec['nom_recette'].' (Ajoutée le ' . $rec['date_ajout_fr'] . ' par '. $rec['pseudo'] . ')</h3><br>';
echo 'Nombre de personnes : '. $rec['nombre_personnes']. '<br>';
echo 'Temps de préparation : '. $rec['temps_prepa']. '<br>';
echo 'Temps de cuisson : '. $rec['temps_cuiss']. '<br>';
echo 'Ingrédients nécessaires : ';
while($ingr = $ingredients->fetch()){
	
	// On affiche l'ingrédient
	echo '<span id="'. $ingr['nom_ingrédient']. '">' .$ingr['valeur'].' '.$ingr['unite']. ' de ' .$ingr['nom_ingrédient'] . ',</span> ';
	
	// On récupère les caractéristiques nutritionnelles
	$caracteristiques = $bdd->query('SELECT * FROM Avoir_Caracteristiques WHERE nom_ingredient ="'. $ingr['nom_ingrédient'] .'"');	
	echo '<div class="caracteristique" id="'.$ingr['nom_ingrédient'].'_hover">';
	while($carac = $caracteristiques->fetch()){
		echo $carac['nom_caracteristique'] . ' : ' . $carac['valeur'] . ' ' . $carac['unite']. '<br>';
	}
	echo '</div>';
?>
	
	<!--Gestion du hover pour chaque ingrédient -->
	<script>
		var element = document.querySelectorAll('#truc'); //<?php echo $ingr['nom_ingrédient']; ?>
		alert(element);
		/*element.addEventListener('mouseover', function(e){
			e.target.style.top = e.pageX;
			e.target.style.left = e.pageY;*/
		//});
	</script>
<?php

	echo '<br>';
}
?>


</body>
</html>
