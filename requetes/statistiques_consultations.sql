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










-----------------------------------------------------------------------------------------------
-- Pour une catégorie donnée, sélectionner les recettes communes, top et sucré-salé 
-- pour un nombre de personnes donné et après une date donnée
--
SELECT SOUS_REQ1.* FROM 
	(SELECT REQ1.* FROM 
		(SELECT RCS.id_recette,nom_recette 
		FROM Recettes_de_cuisine RCS, Contenir_ingredients C1, Contenir_ingredients C2 
		WHERE RCS.id_recette = C1.id_recette = C2.id_recette 
		AND C1.nom_ingrédient = "miel" AND C2.nom_ingrédient="sel") AS REQ1 
		INNER JOIN (SELECT REQ2.* FROM 
					(SELECT RCT.id_recette,nom_recette, COUNT(N.id_recette) AS c 
					FROM Recettes_de_cuisine RCT, Noter N 
					WHERE RCT.id_recette = N.id_recette AND N.valeur = 3 GROUP BY N.id_recette HAVING c > 5) AS REQ2 
					INNER JOIN (SELECT M.id_recette,M.nom_recette FROM 
						(SELECT R1.id_recette,nom_recette, COUNT(M1.id_recette) AS nb_menus 
							FROM Recettes_de_cuisine R1, Contenir_recette M1 
							WHERE R1.id_recette = M1.id_recette 
							GROUP BY M1.id_recette HAVING nb_menus > 2) AS M 
							INNER JOIN (SELECT N.id_recette,N.nom_recette FROM 
								(SELECT R2.id_recette,nom_recette, COUNT(N2.id_recette) AS nb_notes 
								FROM Recettes_de_cuisine R2, Noter N2 
								WHERE R2.id_recette = N2.id_recette 
								GROUP BY N2.id_recette HAVING nb_notes > 9) AS N INNER JOIN 
									(SELECT R3.id_recette,nom_recette, COUNT(C3.id_recette) AS nb_com 
									FROM Recettes_de_cuisine R3, Commenter C3 
									WHERE R3.id_recette = C3.id_recette 
									GROUP BY C3.id_recette HAVING nb_com > 2) AS C 
									ON N.id_recette = C.id_recette) AS L 
									ON M.id_recette = L.id_recette) AS REQ3 
									ON REQ2.id_recette = REQ3.id_recette) AS REQ4 
									ON REQ1.id_recette = REQ4.id_recette) AS SOUS_REQ1 INNER JOIN 
										(SELECT R.id_recette,nom_recette 
										FROM Recettes_de_cuisine R ,Appartenir_catégorie C 
										WHERE C.nom_catégorie = "Dessert" 
										AND R.id_recette = C.id_recette 
										AND R.nombre_personnes = 6 AND R.date_ajout > "2016-11-10" ) AS SOUS_REQ2 
										ON SOUS_REQ1.id_recette = SOUS_REQ2.id_recette;




-----------------------------------------------------------------------------------------------
-- Pour une catégorie donnée, sélectionner les recettes sucré-salé
--
SELECT SOUS_REQ1.* FROM 
	(SELECT RCS.id_recette,nom_recette 
	FROM Recettes_de_cuisine RCS, Contenir_ingredients C1, Contenir_ingredients C2 
	WHERE RCS.id_recette = C1.id_recette = C2.id_recette 
	AND C1.nom_ingrédient = "miel" 
	AND C2.nom_ingrédient="sel") AS SOUS_REQ1 
		INNER JOIN (SELECT R.id_recette,nom_recette 
					FROM Recettes_de_cuisine R ,Appartenir_catégorie C 
					WHERE C.nom_catégorie = "Dessert" AND R.id_recette = C.id_recette ) AS SOUS_REQ2 
		ON SOUS_REQ1.id_recette = SOUS_REQ2.id_recette;
		
		
		
		
-----------------------------------------------------------------------------------------------
-- Pour une catégorie donnée, sélectionner les recettes populaires (TOP)
--
SELECT SOUS_REQ1.* FROM 
	(SELECT RCT.id_recette,nom_recette, COUNT(N.id_recette) AS c 
	FROM Recettes_de_cuisine RCT, Noter N 
	WHERE RCT.id_recette = N.id_recette 
	AND N.valeur = 3 GROUP BY N.id_recette HAVING c > 5) AS SOUS_REQ1 
		INNER JOIN (SELECT R.id_recette,nom_recette 
					FROM Recettes_de_cuisine R ,Appartenir_catégorie C 
					WHERE C.nom_catégorie = "Dessert" AND R.id_recette = C.id_recette ) AS SOUS_REQ2 
		ON SOUS_REQ1.id_recette = SOUS_REQ2.id_recette;
		
		
		
		
		
		
-----------------------------------------------------------------------------------------------
-- Pour une catégorie donnée, sélectionner les recettes communes
--	
SELECT SOUS_REQ1.* FROM 
	(SELECT M.id_recette,M.nom_recette 
	FROM (SELECT R1.id_recette,nom_recette, COUNT(M1.id_recette) AS nb_menus 
		  FROM Recettes_de_cuisine R1, Contenir_recette M1 
		  WHERE R1.id_recette = M1.id_recette GROUP BY M1.id_recette HAVING nb_menus > 2) AS M 
	INNER JOIN (SELECT N.id_recette,N.nom_recette FROM 
												(SELECT R2.id_recette,nom_recette, COUNT(N2.id_recette) AS nb_notes 
												FROM Recettes_de_cuisine R2, Noter N2 
												WHERE R2.id_recette = N2.id_recette 
												GROUP BY N2.id_recette HAVING nb_notes > 9) AS N 
												INNER JOIN (SELECT R3.id_recette,nom_recette, COUNT(C3.id_recette) AS nb_com 
															FROM Recettes_de_cuisine R3, Commenter C3 
															WHERE R3.id_recette = C3.id_recette 
															GROUP BY C3.id_recette HAVING nb_com > 2) AS C 
															ON N.id_recette = C.id_recette) AS L 
															ON M.id_recette = L.id_recette) AS SOUS_REQ1 
															INNER JOIN (SELECT R.id_recette,nom_recette 
																		FROM Recettes_de_cuisine R ,Appartenir_catégorie C 
																		WHERE C.nom_catégorie = "Dessert" AND R.id_recette = C.id_recette ) AS SOUS_REQ2 
																		ON SOUS_REQ1.id_recette = SOUS_REQ2.id_recette;

