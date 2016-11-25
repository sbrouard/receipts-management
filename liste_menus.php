<?php include("ouvrir_base.php"); ?>

<!DOCTYPE html>

<html>

<?php include("head_html.php"); ?>

<body>

<h2>Liste des recettes</h2>

<?php 
// On récupère le contenu de la table Recettes_de cuisine
$menus = $bdd->query('SELECT id_menu,nom_menu FROM Menu');
while ($men = $menus->fetch()){

echo '<p> <a href="affichage_menu.php?id_recette='.$men['id_menu'].'">'. $men['nom_menu'].'</a> </p>';
}

?>

</body>

</html>
