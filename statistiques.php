<?php include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>
<?php include("table_matieres.php"); ?>
<section id="contenu">
	
<h3 id="truc">Nombre de recettes créées par catégorie depuis le début de l'année :</h3><br>
	
<?php	

// On récupère les catégories 
$categories = $bdd->query('SELECT C.nom_categorie, COUNT(A.id_recette) AS nb_recettes
							FROM Categories C, Recettes_de_cuisine R, Appartenir_catégorie A
							WHERE A.id_recette = R.id_recette AND A.nom_catégorie = C.nom_categorie 
							GROUP BY C.nom_categorie');
							

// On affiche le tout
while($cat = $categories->fetch()){
	// On affiche les catégories
	echo '<p class="nom_categorie" id="'. $cat['nom_categorie']. '">' .$cat['nom_categorie']. ' : ' .$cat['nb_recettes']. '</p> ';
	echo '</span>';

}
?>

<h3 id="truc">Classement des recettes :</h3><br>

	
<?php	

// On récupère les recettes 
$recettes = $bdd->query('SELECT R.nom_recette, N.valeur
							FROM Recettes_de_cuisine R, Noter N
							WHERE R.id_recette = N.id_recette 
							ORDER BY N.valeur
							DESC');
							

// On affiche le tout
while($rec = $recettes->fetch()){
	// On affiche les recettes triées par note
	echo '<p class="nom_recette" id="'. $rec['nom_recette']. '">' .$rec['nom_recette']. ' : ' .$rec['valeur']. '</p> ';
	echo '</span>';

}
?>

<?php 

if(isset($_SESSION['pseudo'])){
	
	// On récupère les moyennes des notes sur les recettes par menu
	echo '<h3 id="truc">Moyenne des notes sur les recettes de mes menus :</h3><br>';
	$menus = $bdd->query('SELECT M.nom_menu, AVG(N.valeur) AS moyenne
							FROM Menu M, Contenir_recette C, Recettes_de_cuisine R, Noter N
							WHERE R.id_recette = N.id_recette AND M.id_menu = C.id_menu AND R.id_recette = C.id_recette
							GROUP BY M.nom_menu
							ORDER BY moyenne DESC');
							
	// On affiche le tout
	while($me = $menus->fetch()){
		// On affiche les catégories
		echo '<p class="nom_menu" id="'. $me['nom_menu']. '">' .$me['nom_menu']. ' : ' .$me['moyenne']. '</p> ';
		echo '</span>';

}
							
	
}
	
?>

<h3 id="truc">Classement des ingrédients :</h3><br>

	
<?php	

// On récupère les ingrédients
/*$ingrédients = $bdd->query('SELECT I.nom_ingredient, (SELECT AVG(N.valeur)
						FROM Ingredients I, Noter N, Recettes_de_cuisine R, Contenir_ingredients C
						WHERE C.nom_ingrédient = I.nom_ingredient AND C.id_recette = R.id_recette AND N.id_recette = R.id_recette
						GROUP BY N.id_recette)*((SELECT valeur FROM Avoir_Caracteristiques WHERE unite = "kcal")/(SELECT SUM(valeur) FROM Avoir_Caracteristiques WHERE unite = "kcal")) 
						FROM Ingredients I');*/

//OK
$moyenne_par_ingrédient = $bdd->query('SELECT C.nom_ingrédient, AVG(Mo) AS Av 
						FROM (SELECT R.id_recette, AVG(N.valeur) AS Mo
						FROM Noter N, Recettes_de_cuisine R
						WHERE N.id_recette = R.id_recette
						GROUP BY N.id_recette) A, Contenir_ingredients C
						WHERE A.id_recette = C.id_recette
						GROUP BY C.nom_ingrédient');
//OK					
$ratio_calories = $bdd->query('SELECT nom_ingredient,(valeur/ 
						(SELECT SUM(valeur) 
						FROM Avoir_Caracteristiques 
						WHERE unite = "kcal")) AS S 
						FROM Avoir_Caracteristiques 
						WHERE unite = "kcal"');
//OK
$nb_com_par_rec = $bdd->query('SELECT id_recette, COUNT(texte) AS N
						FROM Commenter
						GROUP BY id_recette');
						
$ingredients = $bdd->query('SELECT nom_ingrédient, valeur AS Somme
					FROM Contenir_ingredients');
								
$d;

	
//OK	
while($c = $nb_com_par_rec->fetch()){
	//echo '<p class="nom_ingredient" id="coucou "> '.$c['id_recette'] . ' :' .$c['N']. '</p> ';
	//echo '</span>';
	if( $c['N'] <= 3){
	$d[$c['id_recette']] = '1';
	}
	elseif( $c['N'] <= 10){
	$d[$c['id_recette']] = 2;
	}
	else{
	$d[$c['id_recette']] = 3;
	}
	echo '<p class="nom_ingredient" id="coucou "> '.$c['id_recette'] . ' :' .$d[$c['id_recette']]. '</p> ';
	echo '</span>';
	
}					
	
while($i = $ingredients->fetch()){		
		$i['Somme'] = 0;
		$recettes = $bdd->query('SELECT C.id_recette FROM Contenir_ingredients C 
		WHERE C.nom_ingrédient = "' . $i['nom_ingrédient'] );
		while($e = $recettes->fetch()){			
			$i['Somme'] += $d[$e['id_recette']];	
			echo 'ajout';	
		}
	echo '<p class="nom_ingredient" id="coucou "> '.$d['nom_ingrédient'] . ' ::' .$d['Somme']. '</p> ';
	echo '</span>';
}

//while($c = $somme_coef_com->fetch()){
//	echo '<p class="nom_ingredient" id="'. $a['Mo']. '">' .$a['nom_ingrédient']. ' :' .$a['K']. '</p> ';
//	echo '</span>';


while($b = $ratio_calories->fetch()){
	echo '<p class="nom_ingredient" id="coucou "> '.$b['nom_ingredient'] . ' :' .$b['S']. '</p> ';
	echo '</span>';
}

while($a = $moyenne_par_ingrédient->fetch()){
	echo '<p class="nom_ingredient" id="'. $a['Mo']. '">' .$a['nom_ingrédient']. ' :' .$a['K']. '</p> ';
	echo '</span>';
	
							

// On affiche le tout
//while($in = $ingrédients->fetch()){
	// On affiche les recettes triées par note
	//echo '<p class="nom_ingredient" id="'. $in['nom_ingredient']. '">' .$in['nom_ingredient']. ' : ?? </p> ';
	//echo '</span>';

}
?>
		
</section>		
</body>
</html>
