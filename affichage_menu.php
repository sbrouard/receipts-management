<?php include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>
<?php include("table_matieres.php"); ?>
<section id="contenu">
<?php	
// On récupère le contenu du menu dans la table Menu
$menus = $bdd->query('SELECT M.id_menu, M.nom_menu, I.pseudo
						FROM Menu AS M INNER JOIN Internaute AS I ON M.id_internaute = I.id_internaute
						WHERE id_menu =' . $_GET['id_menu']);
						
// On récupère les informations du menu					
$me = $menus->fetch();
							

// On récupère les catégories du menu
$catégories = $bdd->query('SELECT DISTINCT nom_catégorie
							FROM Recettes_de_cuisine AS R, Appartenir_catégorie AS A, Contenir_recette AS C
							WHERE R.id_recette = C.id_recette AND R.id_recette = A.id_recette AND C.id_menu ='. $_GET['id_menu']);
							

// Affichage du menu
echo '<h3 id="truc">'. $me['nom_menu'].' (Ajouté par '. $me['pseudo'] . ')</h3><br>';
while($cat = $catégories->fetch()){
	// On affiche les catégories
	echo '<u class="nom_catégorie" id="'. $cat['nom_catégorie']. '">' .$cat['nom_catégorie']. ': </u> ';
	
	//On récupère les recettes de la catégorie
	$recettes = $bdd->query('SELECT R.id_recette, R.nom_recette
							FROM Recettes_de_cuisine AS R, Contenir_recette AS C, Appartenir_catégorie AS A 
							WHERE R.id_recette = C.id_recette AND R.id_recette = A.id_recette AND A.nom_catégorie = "'. $cat['nom_catégorie']. '"AND C.id_menu ='. $_GET['id_menu']);
	
	// On les affiche 
	while($rec = $recettes->fetch()){
		echo '<p> <a href="affichage_recette.php?id_recette='.$rec['id_recette'].'">'. $rec['nom_recette'].'</a> </p>';
	}
	echo '</span>';

}

// Récupération du pseudo de la personne ayant créé le menu
$t_pseudo = $bdd->query('SELECT DISTINCT pseudo 
						FROM Menu INNER JOIN Internaute 
						ON Menu.id_internaute = Internaute.id_internaute 
						WHERE id_menu='.$_GET['id_menu']);
						
$pseudo = $t_pseudo->fetch();

// Lien vers la modification du menu si l'utilisateur connecté est le créateur du menu
if(isset($_SESSION['pseudo']) && !empty($_SESSION['pseudo']) && ($_SESSION['pseudo'] == $pseudo['pseudo']) && !isset($_GET['modif'])){
	echo '<br><br><a href="affichage_menu.php?id_menu='.$_GET['id_menu'].'&modif=1">Modifier mon menu</a>';
}

// Formulaire de modification du menu
if(isset($_GET['modif'])){
	// Ajouter recette
					
					// On récupère le contenu de la table Categories
				$categories = $bdd->query('SELECT nom_categorie FROM Categories');
				//On affiche les lignes une à une:
				echo '<p>  <label for="recettes">Quelle recette souhaitez-vous ajouter ?</label><br />' ;
				echo ' <select name="recettes" id="recettes" onchange="ajouter_recette();"> <option disabled selected>Ajouter une recette</option>' ;

				while ($rec = $categories->fetch()){
					
					echo '<optgroup label="' . $rec['nom_categorie'] . '">';
					
					// On récupère les recettes appartenant à chaque catégorie 
					$recettes = $bdd->query('SELECT Recettes_de_cuisine.id_recette AS id_recette,nom_recette FROM Recettes_de_cuisine, Appartenir_catégorie WHERE Recettes_de_cuisine.id_recette = Appartenir_catégorie.id_recette AND Appartenir_catégorie.nom_catégorie = \''. $rec['nom_categorie'] .'\'' );
					//On affiche les lignes une à une:
					while ($rec = $recettes->fetch()){
							echo '<option value="' . $rec['id_recette'] .','.$rec['nom_recette'].'">' . $rec['nom_recette'];
					};   
						
					echo '</optgroup> ';   
					
				}    

					echo '</select>   </p>';
	
	// Enlever recette
}

?>
<p id="recettes_menu">

</p>









<!-- A comprendre -->
<?php if(isset($_GET['modif'])){ ?>
	
<form method="post" action="#" id="form" >
	<input type="hidden" name="nom_menu" id="nom_menu_form" value="" required="required" />
	<input type="hidden" name="nb_recettes" id="nb_recettes" value="0" />
</form>
<button onclick="modifier_menu()">Ajouter les recettes sélectionnées</button>

<?php } ?>









<?php
// a comprendre
if(isset($_POST['nb_recettes'])){
	if($_POST['nb_recettes'] <= 0){
		echo "Veuillez entrer au moins une recette<br>";
	}
	else{
		$result_id = $bdd->query('SELECT id_internaute FROM Internaute WHERE pseudo = "' . $_SESSION['pseudo'] .'"');
		$id_internaute = $result_id->fetch();
		$id_internaute = $id_internaute['id_internaute'];
		$bdd->exec('INSERT INTO Menu(nom_menu,id_internaute) VALUES("' . $_POST['nom_menu'] .'","'. $id_internaute .'")');
		$menu_id=$bdd->lastInsertId();
		for($i = 1; $i < $_POST['nb_recettes']+1;$i++){
			if(isset($_POST['recette' . $i])){
				$bdd->exec('INSERT INTO Contenir_recette(id_recette,id_menu) VALUES("' . $_POST['recette' . $i] .'","' . $menu_id .'")'); 
				
			}
		}
		echo "Le menu a bien été créé<br>";
	}
	
}

?>













<script>
var nb_recettes = 0;

// A changer
function modifier_menu(){
	document.getElementById("nom_menu_form").value = document.getElementById("nom_menu").value;
	document.getElementById('form').submit();
}

// a comprendre
function ajouter_recette(){
	nb_recettes++;
	var value = document.getElementById('recettes').value.split(',');
	document.getElementById('recettes_menu').innerHTML += value[1] +  '<br>';
	document.getElementById('form').innerHTML += "<input type='hidden' name='recette"+nb_recettes+"' value='" + value[0] + "' /> <br>";
	document.getElementById('nb_recettes').value = nb_recettes;
	}
</script>


</section>
</body>
</html>
