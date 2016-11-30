<?php include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>
	
<br>
<!-- Revenir à la liste des recettes -->
<a href="liste_recettes.php">Revenir à la liste des recettes</a><br>
	
<!-- Revenir à la recette initiale -->
<a href="affichage_recette.php?id_recette=<?php echo $_GET['id_recette'] ?>">Revenir à l'affichage de départ de la recette</a><br>
	
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


// Note moyenne et nombre de votes
$notation = $bdd->query('SELECT avg(valeur) AS note, count(id_internaute) AS nb_votes
					FROM Noter
					WHERE id_recette='. $_GET['id_recette'].
					' GROUP BY id_recette');

$note= $notation->fetch();
echo '<br>Note moyenne: '.$note['note'].'/3 ('.$note['nb_votes'].' votes)<br>';





// Ma note

//Récup id internaute
$id_internaute = 0;
if(isset($_SESSION['pseudo']) && !empty($_SESSION['pseudo'])){
	$intern = $bdd->query('SELECT id_internaute FROM Internaute WHERE pseudo="'.$_SESSION['pseudo'].'"');
	$i = $intern->fetch();
	$id_internaute = $i['id_internaute'];
}

// Traitement de la note modifiée
if(isset($_SESSION['pseudo']) && !empty($_SESSION['pseudo']) && isset($_POST['note']) && !empty($_POST['note'])){
	if($_GET['deja_note'] == 1){
	 $bdd->exec('UPDATE Noter SET valeur='.$_POST['note'].' WHERE id_internaute="'.$id_internaute.'"');
	}
	else if($_GET['deja_note'] == 0){
		$bdd->exec('INSERT INTO Noter(valeur, id_internaute, id_recette) VALUES('.$_POST['note'].','.$id_internaute.','.$_GET['id_recette'].')');
	}	
	header('Location: affichage_recette.php?id_recette='.$_GET['id_recette']);
}

// Page pour modifier la note
if(isset($_GET['modifier_note']) && !empty($_GET['modifier_note']) && isset($_SESSION['pseudo']) && !empty($_SESSION['pseudo'])){
	$ma_note = $bdd->query('SELECT valeur FROM Noter WHERE id_internaute ='.$id_internaute.' AND id_recette='. $_GET['id_recette']);
	if($note = $ma_note->fetch()){
		echo '<br>Ma note: '.$note['valeur'].'<br>';
		echo  '<form method="post" action="affichage_recette.php?id_recette='.$_GET['id_recette'].'&deja_note='.$_GET['deja_note'].'">
				<p>
				Veuillez indiquer votre nouvelle note: <br>
				<input type="radio" name="note" value="1" id="note1"> <label for="note1">1</label><br>
				<input type="radio" name="note" value="2" id="note2"> <label for="note2">2</label><br>
				<input type="radio" name="note" value="3" id="note3" checked> <label for="note3">3</label><br>
				<input type="submit" value="Envoyer">
				</p>
				</form>';
	}
	else{
		echo '<br>Ma note: Vous n\'avez pas encore noté cette recette.<br>';
		echo  '<form method="post" action="affichage_recette.php?id_recette='.$_GET['id_recette'].'&deja_note='.$_GET['deja_note'].'">
				<p>
				Veuillez indiquer votre nouvelle note: <br>
				<input type="radio" name="note" value="1" id="note1"> <label for="note1">1</label><br>
				<input type="radio" name="note" value="2" id="note2"> <label for="note2">2</label><br>
				<input type="radio" name="note" value="3" id="note3" checked> <label for="note3">3</label><br>
				<input type="submit" value="Envoyer">
				</p>
				</form>';
	}
}

// Affichage de base de la note
if((!isset($_GET['modifier_note']) || empty($_GET['modifier_note'])) && (isset($_SESSION['pseudo']) && !empty($_SESSION['pseudo']))){
	$ma_note = $bdd->query('SELECT valeur FROM Noter WHERE id_internaute ='.$id_internaute.' AND id_recette='.$_GET['id_recette']);
	if($note = $ma_note->fetch()){
		echo '<br>Ma note: '.$note['valeur'].'<br>';
		echo '<a href="affichage_recette.php?id_recette='. $_GET['id_recette'].'&modifier_note=1&deja_note=1">Modifier ma note</a><br>';
	}
	else{
		echo '<br>Ma note: Vous n\'avez pas encore noté cette recette.<br>';
		echo '<a href="affichage_recette.php?id_recette='. $_GET['id_recette'].'&modifier_note=1&deja_note=0">Modifier ma note</a><br>';
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







// Description actuelle
$descriptions = $bdd->query('SELECT id_description, texte,
							DATE_FORMAT(date_debut, \'%d/%m/%Y\') AS debut_description,
							DATE_FORMAT(date_fin, \'%d/%m/%Y\') AS fin_description
							FROM Descriptions WHERE	id_recette='.$_GET['id_recette'].
							' ORDER BY date_debut DESC');
if($description_actuelle = $descriptions->fetch()){
	echo '<br>Consignes actuelles de préparation de la recette (écrit le '.$description_actuelle['debut_description']. ') : <br>' .$description_actuelle['texte'].'<br>';
}
else{
	echo '<br>Consignes actuelles de préparation de la recette: Aucune description n\'est fournie pour cette recette<br>';
}


// Afficher descriptions précédentes
if(isset($_GET['anciennes_descriptions']) && !empty($_GET['anciennes_descriptions'])){
	$compteur_fetch = 0;
	while($description = $descriptions->fetch()){
		$compteur_fetch += 1;
		echo '<br>Description du '.$description['debut_description']. ' au '.$description['fin_description'].' : <br>' .$description['texte'].'<br>';
	}
	if($compteur_fetch == 0){
		echo '<br> Aucune autre description <br>';
	}
}
else{
	echo '<a href="affichage_recette.php?id_recette='.$_GET['id_recette'].'&anciennes_descriptions=1">Voir les anciennes descriptions de la recette</a>';
}



// Affichage des commentaires
echo '<br><br><br>Commentaires: <br><br>';
$commentaires = $bdd->query('SELECT date, DATE_FORMAT(date, \'%d/%m/%Y à %hh%imin%ss\') AS date_fr,
							texte, pseudo, id_recette, Internaute.id_internaute
							FROM Commenter INNER JOIN Internaute
							ON Commenter.id_internaute = Internaute.id_internaute
							where id_recette='.$_GET['id_recette'].
							' ORDER BY date DESC');
$already_comment = 0;
$compteur_fetch = 0;
while($com = $commentaires->fetch()){
	$compteur_fetch += 1;
	echo 'Le '. $com['date_fr'] . ' par ' . $com['pseudo']. ':<br>';
	echo $com['texte'];
	
	if(isset($_SESSION) && !empty($_SESSION)){
		if($com['pseudo']==$_SESSION['pseudo']){
			$already_comment += 1;
			if(!isset($_GET['modif_com'])){
				echo '<br><a href="affichage_recette.php?id_recette='.$_GET['id_recette'].'&modif_com=1">Modifier mon commentaire</a>';
			}
		}
	}
	// Traitement du commentaire changé
	if(isset($_POST['com']) && !empty($_POST['com'])){
		$bdd->exec('UPDATE Commenter SET texte="'.$_POST['com'].'", date=NOW() WHERE id_recette='.$_GET['id_recette'].' AND id_internaute='.$id_internaute);
		header('Location: affichage_recette.php?id_recette='.$_GET['id_recette']);
	}
	// Proposition de commentaire changé
	if(isset($_GET['modif_com']) && !empty($_GET['modif_com']) && (!isset($_POST['com']) || empty($_POST['com']))){
		echo '<form method="post" action="affichage_recette.php?id_recette='.$_GET['id_recette'].'">
				<label for="com">Modifier mon commentaire: <br></label>
				<textarea name="com" id="com" rows="5" cols="49" maxlength="255" required></textarea></textarea>
				<input type="submit" value="Envoyer mon commentaire">
			</form>';
	}
	
	echo '<br><br>';
}
if($compteur_fetch ==0){
	echo 'Cette recette n\'a pas encore été commentée <br><br>';
}




// Commenter 
if(!$already_comment && isset($_SESSION) && !empty($_SESSION)){
	// Traitement du commentaire
	if(isset($_POST['com'])){
		$bdd->exec('INSERT INTO Commenter(date,texte,id_internaute,id_recette)
					VALUES(NOW(),"'.$_POST['com'].'",'.$id_internaute.','.$_GET['id_recette'].')');
		header('Location: affichage_recette.php?id_recette='.$_GET['id_recette']);
	}
	// Proposition de commentaire
	if(!isset($_POST['com'])){
		echo '<form method="post" action="affichage_recette.php?id_recette='.$_GET['id_recette'].'">
				<label for="com">Rajouter un commentaire: <br></label>
				<textarea name="com" id="com" rows="5" cols="49" maxlength="255" required></textarea></textarea>
				<input type="submit" value="Envoyer mon commentaire">
			</form>';
	}
}

?>


	
</body>
</html>
