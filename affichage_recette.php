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
echo 'Ingrédients nécessaires <i>(survoler pour avoir les caracteristiques)</i>: ';
while($ingr = $ingredients->fetch()){
	
	// On affiche l'ingrédient
	echo '<u class="nom_ingredient" id="'. $ingr['nom_ingrédient']. '">' .$ingr['valeur'].' '.$ingr['unite']. ' de ' .$ingr['nom_ingrédient'] . ',</u> ';
	
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







// Affichage des menus dont la recette fait partie
echo '<br>Menus dont cette recette fait partie: ';
$menus = $bdd->query('SELECT * 
					FROM (Menu INNER JOIN Contenir_recette 
					ON Menu.id_menu = Contenir_recette.id_menu) 
					WHERE id_recette =' . $_GET['id_recette']);
while($menu=$menus->fetch()){
	echo '<a href="affichage_menu.php?id_menu='. $menu['id_menu']. '">' . $menu['nom_menu']. '</a>, ';
}
echo '<br>';






// Affichage des commentaires
echo '<br><br><br>Commentaires: <br><br>';
$commentaires = $bdd->query('SELECT date, DATE_FORMAT(date, \'%d/%m/%Y à %hh%imin%ss\') AS date_fr,
							texte, pseudo, id_recette, Internaute.id_internaute
							FROM Commenter INNER JOIN Internaute
							ON Commenter.id_internaute = Internaute.id_internaute
							where id_recette='.$_GET['id_recette'].
							' ORDER BY date DESC');
$already_comment = 0;
while($com = $commentaires->fetch()){
	echo 'Le '. $com['date_fr'] . ' par ' . $com['pseudo']. ':<br>';
	echo $com['texte'].'<br><br>';
	
	if(isset($_SESSION) && !empty($_SESSION)){
		if($com['pseudo']==$_SESSION['pseudo']){
			$already_comment += 1;
		}
	}
}




// Commenter 
if(!$already_comment && isset($_SESSION) && !empty($_SESSION)){
	$internaut_connected = $bdd->query('SELECT id_internaute FROM Internaute WHERE pseudo="'.$_SESSION['pseudo'].'"');
	$internaute = $internaut_connected->fetch();	
	
	if(isset($_POST['com'])){
		$bdd->exec('INSERT INTO Commenter(date,texte,id_internaute,id_recette)
					VALUES(NOW(),"'.$_POST['com'].'",'.$internaute['id_internaute'].','.$_GET['id_recette'].')');
		header('Location: affichage_recette.php?id_recette='.$_GET['id_recette']);
	}
	if(!isset($_POST['com'])){
		echo '<form method="post" action="affichage_recette.php?id_recette='.$_GET['id_recette'].'">
				<label for="com">Rajouter un commentaire: <br></label>
				<textarea name="com" id="com" rows="5" cols="49" maxlength="255"></textarea></textarea>
				<input type="submit" value="Envoyer mon commentaire">
			</form>';
	}
}

?>


	
</body>
</html>
