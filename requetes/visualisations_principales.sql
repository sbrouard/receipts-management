-----------------------------------------------------------------------------------------------
-- Sélectionner les informations principales de la recette 3
--
SELECT R.id_recette, 
       nom_recette, 
       DATE_FORMAT(date_ajout, '%d/%m/%Y') AS date_ajout_fr, 
       nombre_personnes, 
       DATE_FORMAT(temps_preparation, '%Hh %imin') AS temps_prepa, 
       DATE_FORMAT(temps_cuisson, '%Hh %imin') AS temps_cuiss, 
       pseudo
FROM Recettes_de_cuisine AS R 
INNER JOIN Internaute AS I 
ON R.id_internaute = I.id_internaute
WHERE R.id_recette = 3;





-----------------------------------------------------------------------------------------------
-- Sélectionner les ingrédients appartenant à la recette 3 et leurs caractéristiques
--
SELECT * FROM Contenir_ingredients WHERE id_recette = 3;






-----------------------------------------------------------------------------------------------
-- Sélectionner les caractéristiques d'un ingrédient
--
SELECT * FROM Avoir_Caracteristiques WHERE nom_ingredient = 'salade'; 





-----------------------------------------------------------------------------------------------
-- Sélectionner les descriptions de la recette 3 ordonnées selon l'ordre inverse 
-- de leur date de début et l'ordre inverse de leurs identifiants
--
SELECT id_description, texte,
       DATE_FORMAT(date_debut, '%d/%m/%Y') AS debut_description,
       DATE_FORMAT(date_fin, '%d/%m/%Y') AS fin_description
       FROM Descriptions WHERE	id_recette=3 
       ORDER BY date_debut DESC, id_description DESC;
       
       
       
       
-----------------------------------------------------------------------------------------------
-- Sélectionner le nombre d'internautes ayant voté pour la recette 3 
-- et la moyenne des notes
--
SELECT avg(valeur) AS note, 
       count(id_internaute) AS nb_votes
       FROM Noter
       WHERE id_recette= 3
       GROUP BY id_recette;




-----------------------------------------------------------------------------------------------
-- Sélectionner la note de l'internaute 1 sur la recette 3
--

SELECT valeur FROM Noter WHERE id_internaute =1 AND id_recette=3;





-----------------------------------------------------------------------------------------------
-- Sélectionner les menus dont la recette 3 fait partie
--
SELECT * FROM Menu INNER JOIN Contenir_recette ON Menu.id_menu = Contenir_recette.id_menu
WHERE id_recette =3;







-----------------------------------------------------------------------------------------------
-- Sélectionner les commentaires de la recette 3 et leurs informations (auteur, date)
--
SELECT texte, pseudo, id_recette, Internaute.id_internaute, 
       DATE_FORMAT(date, '%d/%m/%Y à %hh%imin%ss') AS date_fr
       FROM Commenter INNER JOIN Internaute
       ON Commenter.id_internaute = Internaute.id_internaute
       WHERE id_recette=3 
       ORDER BY date DESC;



-----------------------------------------------------------------------------------------------
-- Vérification de l'existance d'un pseudo avec le mot de passe correspondant
--
SELECT EXISTS (SELECT id_internaute FROM Internaute WHERE pseudo = "azertyuiop" AND mot_de_passe = "qwerty") AS pseudo_exists;





-----------------------------------------------------------------------------------------------
-- Test pour savoir si l'ingrédient "salade" existe déjà dans la base (ou si il faut le rajouter)
--
SELECT EXISTS (SELECT * FROM Ingredients WHERE nom_ingredient = "salade") AS ingredient_exists;








-----------------------------------------------------------------------------------------------
-- Sélection des informations de la recette 3
--
SELECT R.id_recette, nom_recette, nombre_personnes, pseudo, 
       DATE_FORMAT(date_ajout,'%d/%m/%Y') AS date_ajout_fr, 
       DATE_FORMAT(temps_preparation,'%H %i') AS temps_prepa, 
       DATE_FORMAT(temps_cuisson,'%H %i') AS temps_cuiss, 
       texte AS description
FROM (SELECT R1.id_recette, nom_recette, date_ajout, nombre_personnes, 
     	     temps_preparation, temps_cuisson, texte, id_internaute
	FROM Recettes_de_cuisine AS R1 
	     INNER JOIN (SELECT * FROM Descriptions WHERE date_fin = "0000-00-00") D 
	     ON R1.id_recette = D.id_recette ) AS R 
INNER JOIN Internaute AS I 
ON R.id_internaute = I.id_internaute
WHERE R.id_recette = 3;





-----------------------------------------------------------------------------------------------
-- Sélectionner la catégorie de la recette 3
--
SELECT nom_catégorie FROM Appartenir_catégorie WHERE id_recette = 3;





-----------------------------------------------------------------------------------------------
-- Sélectionner toutes les recettes de la catégorie "Dessert"
--
SELECT Recettes_de_cuisine.id_recette AS id_recette, 
       nom_recette 
FROM Recettes_de_cuisine INNER JOIN Appartenir_catégorie 
ON Recettes_de_cuisine.id_recette = Appartenir_catégorie.id_recette 
WHERE Appartenir_catégorie.nom_catégorie = "Dessert";





-----------------------------------------------------------------------------------------------
-- Sélectionner le nom d'un menu à partir de son identifiant
--
SELECT M.id_menu, M.nom_menu, I.pseudo 
FROM Menu AS M INNER JOIN Internaute AS I 
ON M.id_internaute = I.id_internaute
WHERE id_menu =3;




-----------------------------------------------------------------------------------------------
-- Sélectionner la liste des catégories des recettes du menu 3
--
SELECT DISTINCT nom_catégorie 
FROM (Recettes_de_cuisine AS R INNER JOIN Appartenir_catégorie AS A 
     ON R.id_recette = A.id_recette) 
     	INNER JOIN Contenir_recette AS C
	ON R.id_recette = C.id_recette
WHERE C.id_menu =3;




-----------------------------------------------------------------------------------------------
-- Sélectionner les recettes du menu 3 et de la catégorie "Dessert"
--
SELECT R.id_recette, R.nom_recette 
FROM (Recettes_de_cuisine AS R INNER JOIN Contenir_recette AS C
     ON R.id_recette = C.id_recette) 
     	INNER JOIN Appartenir_catégorie AS A 
	ON R.id_recette = A.id_recette 
WHERE A.nom_catégorie = "Dessert" AND C.id_menu =3;





-----------------------------------------------------------------------------------------------
-- Sélectionner le pseudo de l'internaute ayant créé le menu 3
--
SELECT DISTINCT pseudo 
FROM Menu INNER JOIN Internaute 
ON Menu.id_internaute = Internaute.id_internaute 
WHERE id_menu=3;
