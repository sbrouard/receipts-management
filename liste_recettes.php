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

<br>
<a href="./liste_recettes.php?sucre_sale=true" title="consulter les recettes contenant du sucre et du miel">Consulter les recettes "sucré-salé"</a><br><br>
<a href="./liste_recettes.php?top=true" title="top">Consulter les recettes les plus populaires</a><br><br>
<a href="./liste_recettes.php?commune=true" title="recettes communes">Consulter les recettes les plus utilisées</a><br>
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

if(isset($_GET['sucre_sale']) && $_GET['sucre_sale'] == true){
	$sucre_sale = 'SELECT R.id_recette,nom_recette FROM Recettes_de_cuisine R, Contenir_ingredients C1, Contenir_ingredients C2 WHERE R.id_recette = C1.id_recette = C2.id_recette AND C1.nom_ingrédient = "miel"';
}
if(isset($_GET['top']) && $_GET['top'] == true){
	$top = 'SELECT R.id_recette,nom_recette, COUNT(N.id_recette) AS c FROM Recettes_de_cuisine R, Noter N WHERE R.id_recette = N.id_recette AND N.valeur = 3 GROUP BY N.id_recette HAVING c > 2';
}
if(isset($_GET['commune']) && $_GET['commune'] == true){
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
	
if(isset($_GET['sucre_sale']) && $_GET['sucre_sale'] == true){
	$req1 = $sucre_sale;
	$nb_req++;

	if(isset($_GET['top']) && $_GET['top'] == true){
		$req2 = $top;
		$nb_req++;

		if(isset($_GET['commune']) && $_GET['commune'] == true){
			$req3 = $commune;
			$nb_req++;
		}
	}	

	else if(isset($_GET['commune']) && $_GET['commune'] == true){
		$req2 = $commune;
		$nb_req++;
	}
}
else if(isset($_GET['top']) && $_GET['top'] == true){
	$req1 = $top;
	$nb_req++;

	if(isset($_GET['commune']) && $_GET['commune'] == true){
		$req2 = $commune;
		$nb_req++;
	}
}
else if(isset($_GET['commune']) && $_GET['commune'] == true){
	$req1 = $commune;
	$nb_req++;
}


// Regroupement des requêtes sucré-salé, top et communes

if ($nb_req == 1){
	$sous_req1 = $req1;
}
else if ($nb_req == 2){
	$sous_req1 = 'SELECT * FROM ('. $req1 .' AS REQ1 INNER JOIN ('. $req2 .') AS REQ2 ON REQ1.id_recette = REQ2.id_recette';
}
else if ($nb_req == 3){

}
  


// test
if ($nb_req > 0){
	$recettes = $bdd->query($sous_req1);
	while($rec = $recettes->fetch()){
			echo '<p> <a href="affichage_recette.php?id_recette='.$rec['id_recette'].'">'. $rec['nom_recette'].'</a> </p>';
	}
}



























/*requette sur :
-categorie
-nb_personnes
-date
*/



/*
else{
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
}
*/

?>




</section>


</body>

</html>
