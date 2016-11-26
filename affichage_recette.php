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
	echo '<span class="caracteristique" id="'.$ingr['nom_ingrédient'].'_hover" style="">';
	$compteur_caracteristiques = 0;
	while($carac = $caracteristiques->fetch()){
		$compteur_caracteristiques +=1; 
		echo $carac['nom_caracteristique'] . ' : ' . $carac['valeur'] . ' ' . $carac['unite'].'<br>';
	}
	echo '</span>';

	if($compteur_caracteristiques != 0){
		echo 
			'<!--Gestion du hover pour chaque ingrédient -->
			<script type="text/javascript">
				var element = document.getElementById(\''.$ingr['nom_ingrédient'].'\'); 
				element.addEventListener(\'mouseover\', function(e){
					var el = document.getElementById(\''. $ingr['nom_ingrédient'].'_hover\');
					el.style.position = \'absolute\';
					el.style.left = e.clientX + 30 +\'px\';
					el.style.top = e.clientY + 30 +\'px\';
					
				});
				element.addEventListener(\'mouseout\', function(e){
					var el = document.getElementById(\''.$ingr['nom_ingrédient'].'_hover\');
					el.style.position = \'absolute\';
					el.style.left = \'-100px\';
					el.style.top = \'-100px\';
				});
			</script> ';
	}
}
?>


	
</body>
</html>
