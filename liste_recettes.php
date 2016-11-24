<?php include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>

<h2>Liste des recettes (test)</h2>

<?php 
// On récupère le contenu de la table Recettes_de cuisine
$recettes = $bdd->query('SELECT nom_recette FROM Recettes_de_cuisine');
//On affiche les lignes une à une:
while ($rec = $recettes->fetch()){

echo '<p> <a href="liste_recettes.php?nomrecette='.$rec['nom_recette'].'">'. $rec['nom_recette'].'</a> </p>';
//.'</b> Temps de préparation: ' . $rec['temps_preparation'] . '  Temps de cuisson: ' . $rec['temps_cuisson']
}

?>

</body>

</html>
