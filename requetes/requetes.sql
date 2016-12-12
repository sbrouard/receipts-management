
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
-- Supprimer la recette 3
--
DELETE FROM Recettes_de_cuisine where id_recette=3;





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
-- Mise à jour de la note de l'internaute 4
--
UPDATE Noter SET valeur=2 WHERE id_internaute=4;






-----------------------------------------------------------------------------------------------
-- Mise à jour de la note de l'internaute 4
--
INSERT INTO Noter(valeur, id_internaute, id_recette) VALUES(1,2,3);






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
-- Mise à jour commentaire de l'internaute 2 pour la recette 3
--
UPDATE Commenter SET texte="nouveau commentaire", date=NOW() WHERE id_recette=3  AND id_internaute=2;





-----------------------------------------------------------------------------------------------
-- Insertion d'un nouveau commentaire de l'internaute 1 sur la recette 3
--
INSERT INTO Commenter(date,texte,id_internaute,id_recette) 
VALUES(NOW(),"nouveau commentaire...",1,3);





-----------------------------------------------------------------------------------------------
-- Vérification de l'existance d'un pseudo avec le mot de passe correspondant
--
SELECT EXISTS (SELECT id_internaute FROM Internaute WHERE pseudo = "azertyuiop" AND mot_de_passe = "qwerty") AS pseudo_exists;





-----------------------------------------------------------------------------------------------
-- Insertion d'un nouvel internaute dans la base (mot de passe et pseudo)
--
INSERT INTO Internaute(pseudo,mot_de_passe) VALUES("nouveau pseudo","mot de passe associé");







-----------------------------------------------------------------------------------------------
-- Sélection des menus dont toutes les recettes ont été créées après le 25 novembre 2016
--
SELECT id_menu,nom_menu FROM Menu 
WHERE id_menu NOT IN 
(SELECT M.id_menu 
FROM Menu M,Contenir_recette C, Recettes_de_cuisine R 
WHERE R.date_ajout < "2016-11-25" 
AND R.id_recette = C.id_recette 
AND C.id_menu= M.id_menu)  
AND id_menu IN (SELECT id_menu FROM Contenir_recette);







-----------------------------------------------------------------------------------------------
-- Sélection des menus ne contenant que des ingrédients peu caloriques (< 250 kcal)
--
SELECT DISTINCT Contenir_recette.id_menu, Menu.nom_menu 
FROM Contenir_recette INNER JOIN Menu
ON Contenir_recette.id_menu = Menu.id_menu 
WHERE Contenir_recette.id_menu NOT IN
(SELECT id_menu FROM Contenir_recette 
WHERE id_recette IN(SELECT Recettes_de_cuisine.id_recette 
FROM Recettes_de_cuisine INNER JOIN Contenir_ingredients
ON Recettes_de_cuisine.id_recette=Contenir_ingredients.id_recette
WHERE nom_ingrédient IN (SELECT nom_ingredient FROM Ingredients 
WHERE nom_ingredient NOT IN (SELECT nom_ingredient FROM Avoir_Caracteristiques WHERE nom_caracteristique="Calories") 
OR nom_ingredient NOT IN (SELECT nom_ingredient FROM Avoir_Caracteristiques WHERE nom_caracteristique="Calories" AND valeur<250))));






-----------------------------------------------------------------------------------------------
-- Affichage des catégories et du nombre de recettes appartenant à chaque catégorie
--
SELECT C.nom_categorie, COUNT(A.id_recette) AS nb_recettes 
FROM (Categories C INNER JOIN Appartenir_catégorie A ON A.nom_catégorie = C.nom_categorie)  
INNER JOIN Recettes_de_cuisine R ON A.id_recette = R.id_recette 
GROUP BY C.nom_categorie;






-----------------------------------------------------------------------------------------------
-- Classement des recettes par la moyenne de leurs notes
--
SELECT R.nom_recette, AVG(N.valeur) AS valeur
FROM Recettes_de_cuisine R INNER JOIN Noter N
ON R.id_recette = N.id_recette
GROUP BY(nom_recette) 
ORDER BY N.valeur DESC;





-----------------------------------------------------------------------------------------------
-- Classement des menus par la moyenne des notes de leurs recettes
--
SELECT M.nom_menu, AVG(N.valeur) AS moyenne
FROM ((Menu M INNER JOIN Contenir_recette C ON M.id_menu = C.id_menu) 
INNER JOIN Recettes_de_cuisine R ON R.id_recette = C.id_recette) 
INNER JOIN Noter N ON R.id_recette = N.id_recette 
GROUP BY M.nom_menu
ORDER BY moyenne DESC;






-----------------------------------------------------------------------------------------------
-- Classement des ingrédients selon leur coefficient de commentaire
--
SELECT Moyenne.nom_ingrédient, Moyenne.Av*Calories.Ratio*Coef.Somme AS Cf
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
						ORDER BY Cf;







-----------------------------------------------------------------------------------------------
-- Modification de la recette 3 (nom, nombre de personnes, temps préparation, temps cuisson)
--
UPDATE Recettes_de_cuisine SET nom_recette = "nouveau nom recette", 
       			       nombre_personnes = 5, 
			       temps_preparation = "01:20:00", 
			       temps_cuisson = "00:45:00" 
WHERE id_recette = 3;





-----------------------------------------------------------------------------------------------
-- Mise à jour de la date de fin d'une description quand une autre description est ajoutée
--
UPDATE Descriptions SET date_fin = CURRENT_DATE 
WHERE date_fin = "0000-00-00" AND id_recette = 3;






-----------------------------------------------------------------------------------------------
-- Insertion dans la base d'une nouvelle description pour la recette 3
--
INSERT INTO Descriptions(date_debut, date_fin ,texte, id_recette) 
VALUES(CURRENT_DATE, "0000-00-00","description actuelle de la recette", 3);





-----------------------------------------------------------------------------------------------
-- Test pour savoir si l'ingrédient "salade" existe déjà dans la base (ou si il faut le rajouter)
--
SELECT EXISTS (SELECT * FROM Ingredients WHERE nom_ingredient = "salade") AS ingredient_exists;






-----------------------------------------------------------------------------------------------
-- Ajout d'un nouvel ingrédient dans la base
--
INSERT INTO Ingredients(nom_ingredient) Values("cornichon");





-----------------------------------------------------------------------------------------------
-- Modifier un ingrédient de la recette 3 (changer nom, quantité, unité)
--
UPDATE Contenir_ingredients 
SET nom_ingrédient = "champignons de paris", valeur = 2, unite = "chapignons" 
WHERE  nom_ingrédient = "champignon" AND id_recette = 3;





-----------------------------------------------------------------------------------------------
-- Rajouter un ingrédient dans la recette 3 (ex: 5 cuillères de miel)
--
INSERT INTO Contenir_ingredients(unite,valeur,id_recette,nom_ingrédient) 
VALUES("cuillères", 5, 3, "miel");




-----------------------------------------------------------------------------------------------
-- Modifier la catégorie de la recette 3
--
UPDATE Appartenir_catégorie SET nom_catégorie = "Apéritif" 
WHERE id_recette = 3;






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
-- Insérer un nouveau menu créé par l'internaute 6
--
INSERT INTO Menu(nom_menu,id_internaute) VALUES("nom du nouveau menu", 6);




-----------------------------------------------------------------------------------------------
-- Ajouter la recette 3 dans le menu 5
--
INSERT INTO Contenir_recette(id_recette,id_menu) VALUES(3,5);




-----------------------------------------------------------------------------------------------
-- Ajouter une nouvelle recette associée à un utilisateur particulier
--
INSERT INTO Recettes_de_cuisine(nom_recette,date_ajout,nombre_personnes,temps_preparation,temps_cuisson,id_internaute) 
VALUES("nom de la nouvelle recette", CURRENT_DATE, 10,"00:35:00","00:15:00",
(SELECT id_internaute FROM Internaute WHERE pseudo="polochon"));




-----------------------------------------------------------------------------------------------
-- Ajouter un nouvel ingrédient en minuscules dans la base
--
INSERT INTO Ingredients(nom_ingredient) Values(LOWER("Epinards"));




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




-----------------------------------------------------------------------------------------------
-- Supprimer le menu 3
--
DELETE FROM Menu where id_menu=3;




-----------------------------------------------------------------------------------------------
-- Sélectionner les recettes sucré-salé
--
SELECT DISTINCT RCS.id_recette, nom_recette 
FROM (Recettes_de_cuisine RCS INNER JOIN Contenir_ingredients C1 ON RCS.id_recette = C1.id_recette) 
     INNER JOIN Contenir_ingredients C2 
     ON C1.id_recette = C2.id_recette 
WHERE C1.nom_ingrédient = "miel"
AND C2.nom_ingrédient="sel";



-----------------------------------------------------------------------------------------------
-- Sélectionner les recettes TOP (notées par plus de 5 internautes à 3)
--
SELECT RCT.id_recette,nom_recette, COUNT(N.id_recette) AS c 
FROM Recettes_de_cuisine RCT INNER JOIN Noter N 
ON RCT.id_recette = N.id_recette 
WHERE N.valeur = 3 GROUP BY N.id_recette HAVING c > 5;




-----------------------------------------------------------------------------------------------
-- Sélectionner les recettes communes
-- (apparaissent dans au moins 3 menus, ont plus de 10 notes et 3 commentaires)
--
SELECT M.id_recette,M.nom_recette 
FROM (SELECT R1.id_recette,nom_recette, COUNT(M1.id_recette) AS nb_menus 
     	     FROM Recettes_de_cuisine R1, Contenir_recette M1 
     	     WHERE R1.id_recette = M1.id_recette 
     	     GROUP BY M1.id_recette HAVING nb_menus > 2) AS M 
     INNER JOIN 
      (SELECT N.id_recette,N.nom_recette FROM (SELECT R2.id_recette,nom_recette, COUNT(N2.id_recette) AS nb_notes 
      	      				      FROM Recettes_de_cuisine R2, Noter N2 
					      WHERE R2.id_recette = N2.id_recette 
					      GROUP BY N2.id_recette HAVING nb_notes > 9) AS N 
      	      INNER JOIN 
	      (SELECT R3.id_recette,nom_recette, COUNT(C3.id_recette) AS nb_com 
	      	      FROM Recettes_de_cuisine R3, Commenter C3 
		      WHERE R3.id_recette = C3.id_recette 
		      GROUP BY C3.id_recette HAVING nb_com > 2) AS C
	      ON N.id_recette = C.id_recette) AS L
     ON M.id_recette = L.id_recette;
