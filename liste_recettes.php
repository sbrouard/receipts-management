<?php include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>

<?php include("table_matieres.php"); ?>
<section id="contenu">

<h2>Liste des recettes</h2>

<form method="post" action="./liste_recettes.php">
<label for="categorie">Sélectionner les recettes de la catégorie: </label><select name="categorie" id="categorie">
<option value="toutes" <?php if(!isset($_POST['categorie']) || $_POST['categorie']) echo "selected"; ?>>Toutes les gatégories
<?php 
$categories = $bdd->query('SELECT nom_categorie FROM Categories'); 
while($cat = $categories->fetch()){
	echo "<option value='" . $cat['nom_categorie'] ."'";
	if (isset($_POST['categorie']) && $_POST['categorie'] ==  $cat['nom_categorie']) 
		echo " selected";
	echo ">". $cat['nom_categorie'];
}
?>
</select>
<span title="laisser vide pour ignorer">
<label for="nb_personnes">pour: </label><input type="number" name="nb_personnes" id="nb_personnes" min=0 style="width:50px;" 
<?php if(isset($_POST['nb_personnes']))
	echo "value='". $_POST['nb_personnes'] ."'";
?>
/><label for="nb_personnes">personnes </label>
<label for="date">ajoutées après le: </label><input type="date" name="date" id="date" placeholder="jj/mm/aaaa" maxlength="10" style="width:80;"
<?php if(isset($_POST['date']))
	echo "value='". $_POST['date'] ."'";
?>
/>
</span><br />
<label for="sucre_sale">Sélectionner seulement les recettes sucré-salé</label><input type="checkbox" name="sucre_sale" id="sucre_sale" 
<?php if(isset($_POST['sucre_sale']) && $_POST['sucre_sale'] == true)
	echo " checked";
?>
/> <br />
<label for="top">Sélectionner seulement les recettes les plus populaires</label><input type="checkbox" name="top" id="top" 
<?php if(isset($_POST['top']) && $_POST['top'] == true)
	echo " checked";
?>
/> <br />
<label for="commune">Sélectionner seulement les recettes les plus utilisées</label><input type="checkbox" name="commune" id="commune" 
<?php if(isset($_POST['commune']) && $_POST['communes'] == true)
	echo " checked";
?>
/> <br />

<input type="submit" value="Sélectionner" />
</form>

<br>
<br><br><br>
<?php 

//Définition des variables necessaires pour stocker
//puis regroouper les requettes sucré-salé, top et communes

$sucre_sale ='';
$top = '';
$commune = '';
$req1 = '';$req2 = '';$req3 = '';
$nb_req = 0;
$sous_req1 = '';


//Petites requettes sucré-salé, top et communes INDEPENDANTES

//if(isset($_GET['sucre_sale']) && $_GET['sucre_sale'] == true){
if(isset($_POST['sucre_sale']) && $_POST['sucre_sale'] == true){
	$sucre_sale = 'SELECT RCS.id_recette,nom_recette FROM Recettes_de_cuisine RCS, Contenir_ingredients C1, Contenir_ingredients C2 WHERE RCS.id_recette = C1.id_recette = C2.id_recette AND C1.nom_ingrédient = "miel"';
}
if(isset($_POST['top']) && $_POST['top'] == true){
	$top = 'SELECT RCT.id_recette,nom_recette, COUNT(N.id_recette) AS c FROM Recettes_de_cuisine RCT, Noter N WHERE RCT.id_recette = N.id_recette AND N.valeur = 3 GROUP BY N.id_recette HAVING c > 2';
}
if(isset($_POST['commune']) && $_POST['commune'] == true){
	$com_menus = 'SELECT R1.id_recette,nom_recette, COUNT(M1.id_recette) AS nb_menus FROM Recettes_de_cuisine R1, Contenir_recette M1 WHERE R1.id_recette = M1.id_recette GROUP BY M1.id_recette HAVING nb_menus > 2';
	$com_notes = 'SELECT R2.id_recette,nom_recette, COUNT(N2.id_recette) AS nb_notes FROM Recettes_de_cuisine R2, Noter N2 WHERE R2.id_recette = N2.id_recette GROUP BY N2.id_recette HAVING nb_notes > 9';
	$com_commentaires = 'SELECT R3.id_recette,nom_recette, COUNT(C3.id_recette) AS nb_com FROM Recettes_de_cuisine R3, Commenter C3 WHERE R3.id_recette = C3.id_recette GROUP BY C3.id_recette HAVING nb_com > 2';
	$commune = 'SELECT M.id_recette,M.nom_recette 
				FROM ('. $com_menus .') AS M 
				INNER JOIN 
				(SELECT N.id_recette,N.nom_recette FROM ('. $com_notes .') AS N 
					INNER JOIN 
					('. $com_commentaires .') AS C
					ON N.id_recette = C.id_recette) AS L
				ON M.id_recette = L.id_recette';
}


//Stockage des requettes dans des variables indépendantes de la requete
	
if(isset($_POST['sucre_sale']) && $_POST['sucre_sale'] == true){
	$req1 = $sucre_sale;
	$nb_req++;

	if(isset($_POST['top']) && $_POST['top'] == true){
		$req2 = $top;
		$nb_req++;

		if(isset($_POST['commune']) && $_POST['commune'] == true){
			$req3 = $commune;
			$nb_req++;
		}
	}	

	else if(isset($_POST['commune']) && $_POST['commune'] == true){
		$req2 = $commune;
		$nb_req++;
	}
}
else if(isset($_POST['top']) && $_POST['top'] == true){
	$req1 = $top;
	$nb_req++;

	if(isset($_POST['commune']) && $_POST['commune'] == true){
		$req2 = $commune;
		$nb_req++;
	}
}
else if(isset($_POST['commune']) && $_POST['commune'] == true){
	$req1 = $commune;
	$nb_req++;
}


// Regroupement des requêtes sucré-salé, top et communes

if ($nb_req == 1){
	$sous_req1 = $req1;
}
else if ($nb_req == 2){
	$sous_req1 = 'SELECT REQ1.* FROM ('. $req1 .') AS REQ1 INNER JOIN ('. $req2 .') AS REQ2 ON REQ1.id_recette = REQ2.id_recette';
}
else if ($nb_req == 3){//reste à faire 
		$sous_req1 = 'SELECT REQ1.* FROM ('. $req1 .') AS REQ1 
		INNER JOIN (SELECT REQ2.* FROM ('. $req2 .') AS REQ2 
			INNER JOIN ('. $req3 .') AS REQ3 
			ON REQ2.id_recette = REQ3.id_recette) AS REQ4
		ON REQ1.id_recette = REQ4.id_recette';

}
  

// A ce stade la requete qui sélectionne les recettes
//sucré-salé et/ou top et/ou communes
// est stocker dans la variable $sous_req1;
// il reste à l'assembler avec la requette de sélection sur les catégorie, le nb de personnes et la date qui se trouve ci-dessous




/*requette sur :
-categorie
-nb_personnes
-date
*/




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
$sous_req2 = 'SELECT R.id_recette,nom_recette FROM '. $from . $where;


// la requette de sélection sur les catégorie, le nb de personnes et la date 
// est à présent stockée dans la variable $sous_req2



// On réuni les "petites" requêtes en la requête principale:
$requette = '';
if ($nb_req > 0){
$requette = 'SELECT SOUS_REQ1.* FROM ('. $sous_req1 .') AS SOUS_REQ1
						INNER JOIN ('. $sous_req2 .') AS SOUS_REQ2 ON SOUS_REQ1.id_recette = SOUS_REQ2.id_recette';
}
else{
	$requette = $sous_req2;
}



$recettes = $bdd->query($requette);


// affichage du résultat ligne par ligne sous forme de lien
while ($rec = $recettes->fetch()){
	echo '<p> <a href="affichage_recette.php?id_recette='.$rec['id_recette'].'">'. $rec['nom_recette'].'</a> </p>';
}


?>




</section>


</body>

</html>
