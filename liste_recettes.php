<?php include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>

<h2>Liste des recettes</h2>

<?php 
// On récupère le contenu de la table Recettes_de cuisine
$recettes = $bdd->query('SELECT id_recette, nom_recette FROM Recettes_de_cuisine');
//On affiche les lignes une à une:
while ($rec = $recettes->fetch()){

echo '<p> <a href="affichage_recette.php?id_recette='.$rec['id_recette'].'">'. $rec['nom_recette'].'</a> </p>';
}

?>

</body>

</html>
