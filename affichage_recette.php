<?php include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>
	
<?php	
// On récupère le contenu de la recette dans la table Recettes_de cuisine
$recettes = $bdd->query('SELECT id_recette, nom_recette, 
						DATE_FORMAT(date_ajout, \'%d/%m/%Y\') AS date_ajout_fr, 
						nombre_personnes, temps_preparation, temps_cuisson, pseudo 
						FROM Recettes_de_cuisine, Internaute 
						WHERE Recettes_de_cuisine.id_internaute = Internaute.id_internaute AND
						id_recette =' . $_GET['id_recette']);
$rec = $recettes->fetch();
echo $rec['pseudo'];
?>
<!--
// On récupère les informations de la recette
$rec = $recettes->fetch();

// Affichage de la recette
echo '<h3>'.$rec['nom_recette'].' (Ajouté le ' . $rec['date_ajout_fr'] . ' par '. $rec['Internaute.pseudo'] . ')</h3><br>';
echo 'Nombre de personnes : <br>';
?>
-->

</body>
</html>
