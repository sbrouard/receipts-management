-----------------------------------------------------------------------------------------------
-- Supprimer la recette 3
--
DELETE FROM Recettes_de_cuisine where id_recette=3;






-----------------------------------------------------------------------------------------------
-- Mise à jour de la note de l'internaute 4
--
UPDATE Noter SET valeur=2 WHERE id_internaute=4;






-----------------------------------------------------------------------------------------------
-- Rajout d'un vote pour l'internaute 2
--
INSERT INTO Noter(valeur, id_internaute, id_recette) VALUES(1,2,3);





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
-- Insertion d'un nouvel internaute dans la base (mot de passe et pseudo)
--
INSERT INTO Internaute(pseudo,mot_de_passe) VALUES("nouveau pseudo","mot de passe associé");





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
-- Supprimer le menu 3
--
DELETE FROM Menu where id_menu=3;
