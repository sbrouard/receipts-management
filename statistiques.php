<?php include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>
<?php include("table_matieres.php"); ?>
<section id="contenu">
	
<h3 id="Nb_rec">Nombre de recettes créées par catégorie depuis le début de l'année :</h3><br>
	
<?php	

// On récupère les catégories 
$categories = $bdd->query('SELECT C.nom_categorie, COUNT(A.id_recette) AS nb_recettes
							FROM Categories C, Recettes_de_cuisine R, Appartenir_catégorie A
							WHERE A.id_recette = R.id_recette AND A.nom_catégorie = C.nom_categorie 
							GROUP BY C.nom_categorie');
							

// On affiche le tout
while($cat = $categories->fetch()){
	// On affiche les catégories
	echo '<p class="nom_categorie" id="'. $cat['nom_categorie']. '">' .$cat['nom_categorie']. ' : ' .$cat['nb_recettes']. '</p>';
	echo '</span>';

}

echo '</br>';
?>

<h3 id="Cla_re">Classement des recettes :</h3><br>

	
<?php	

// On récupère les recettes 
$recettes = $bdd->query('SELECt R.nom_recette, AVG(N.valeur) AS valeur
							FROM Recettes_de_cuisine R, Noter N
							WHERE R.id_recette = N.id_recette
							GROUP BY(nom_recette) 
							ORDER BY N.valeur
						
							DESC');
							

// On affiche le tout
while($rec = $recettes->fetch()){
	// On affiche les recettes triées par note
	echo '<p class="nom_recette" id="'. $rec['nom_recette']. '">' .$rec['nom_recette']. ' : ' .$rec['valeur']. '</p> ';
	echo '</span>';

}

echo '</br>';
?>

<?php 

if(isset($_SESSION['pseudo'])){
	
	// On récupère les moyennes des notes sur les recettes par menu
	echo '<h3 id="Moy_not">Moyenne des notes sur les recettes de mes menus :</h3><br>';
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

echo '</br>';

}
							
	
}
	
?>

<h3 id="Cla_in">Classement des ingrédients :</h3><br>

	
<?php	


/* On calcule la moyenne des notes des recettes enregistrées utilisant l’ingrédient
$moyenne_par_ingredient = $bdd->query('SELECT C.nom_ingrédient, AVG(Mo) AS Av 
						FROM (SELECT R.id_recette, AVG(N.valeur) AS Mo
							FROM Noter N, Recettes_de_cuisine R
							WHERE N.id_recette = R.id_recette
							GROUP BY N.id_recette) A, Contenir_ingredients C
						WHERE A.id_recette = C.id_recette
						GROUP BY C.nom_ingrédient');

//On calcule ratio de calories égal au nombre de calories de l’ingrédient divisé par la moyenne de l’ensemble des calories des ingrédients					
$ratio_calories = $bdd->query('SELECT nom_ingredient,(valeur/ 
						(SELECT SUM(valeur) 
						FROM Avoir_Caracteristiques 
						WHERE unite = "kcal")) AS Ratio 
						FROM Avoir_Caracteristiques 
						WHERE unite = "kcal"');
//On calcule la somme, pour toutes les recettes utilisant l’ingrédient du coefficient de commentaire :
— 1 jusqu’à 3 commentaires,
— 2 jusqu’à 10 commentaires,
— 3 si il y a plus de 10 commentaires.
$somme_coef_comment = $bdd->query('SELECT C.nom_ingrédient, SUM(COEF.coef) AS Somme
						FROM (SELECT DISTINCT C.id_recette,
							CASE
    								WHEN COM.nb_com<=3 THEN 1
   				 				WHEN COM.nb_com<=10 THEN 2
     					 			ELSE 3
   							END	AS coef

							FROM (SELECT id_recette, 
								COUNT(texte) AS nb_com
								FROM Commenter 
								GROUP BY id_recette) COM,
							Commenter C) COEF,
						Contenir_ingredients C
						WHERE COEF.id_recette = C.id_recette
						GROUP BY C.nom_ingrédient');*/

//On réalise la multiplication finale
$classement_fin = $bdd->query('SELECT Moyenne.nom_ingrédient, Moyenne.Av*Calories.Ratio*Coef.Somme AS Cf
						FROM (SELECT C.nom_ingrédient, AVG(Mo) AS Av 
							FROM (SELECT R.id_recette, AVG(N.valeur) AS Mo
								FROM Noter N, Recettes_de_cuisine R
								WHERE N.id_recette = R.id_recette
								GROUP BY N.id_recette) A, Contenir_ingredients C
							WHERE A.id_recette = C.id_recette
							GROUP BY C.nom_ingrédient) Moyenne,
						(SELECT nom_ingredient,(valeur/ 
							(SELECT SUM(valeur) 
							FROM Avoir_Caracteristiques 
							WHERE unite = "kcal")) AS Ratio 
							FROM Avoir_Caracteristiques 
							WHERE unite = "kcal") Calories,
						(SELECT C.nom_ingrédient, SUM(COEF.coef) AS Somme
							FROM (SELECT DISTINCT C.id_recette,
								CASE
    									WHEN COM.nb_com<=3 THEN 1
   				 					WHEN COM.nb_com<=10 THEN 2
     					 				ELSE 3
   								END	AS coef

								FROM (SELECT id_recette, 
									COUNT(texte) AS nb_com
									FROM Commenter 
									GROUP BY id_recette) COM,
								Commenter C) COEF,
							Contenir_ingredients C
							WHERE COEF.id_recette = C.id_recette
							GROUP BY C.nom_ingrédient) Coef
						WHERE Moyenne.nom_ingrédient = Calories.nom_ingredient
						AND Calories.nom_ingredient = Coef.nom_ingrédient
						GROUP BY nom_ingrédient
						ORDER BY Cf');

//Affichage des étapes intermédiaires

/*echo 'ratio calo';
while($b = $ratio_calories->fetch()){
	echo '<p> '.$b['nom_ingredient'] . ' :' .$b['Ratio']. '</p> ';
	echo '</span>';
}

echo 'moyenne';
while($a = $moyenne_par_ingredient->fetch()){
	echo '<p>' .$a['nom_ingrédient']. ' :' .$a['Av']. '</p> ';
	echo '</span>';

}

echo 'com par recette';
while($c = $somme_coef_comment->fetch()){
	echo '<p>' .$c['nom_ingrédient'] . ':' .$c['Somme'] . ' </p> ';
	echo '</span>';

}*/
	

// Classement fin final
while($cla = $classement_fin->fetch()){
	echo '<p class="classement_final" id="' .$cla['nom_ingrédient'] .'">' .$cla['nom_ingrédient'] . ' : ' .$cla['Cf'] . ' </p> ';
	echo '</span>';

}
?>
		
</section>		
</body>
</html>
