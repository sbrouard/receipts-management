
-- 
-- Informations sur une recette
--

SELECT R.id_recette, 
		nom_recette, 
		DATE_FORMAT(date_ajout, '%d/%m/%Y') AS date_ajout_fr, 
		nombre_personnes, 
		DATE_FORMAT(temps_preparation, '%Hh %imin') AS temps_prepa, 
		DATE_FORMAT(temps_cuisson, '%Hh %imin') AS temps_cuiss, 
		pseudo
FROM Recettes_de_cuisine AS R INNER JOIN Internaute AS I ON R.id_internaute = I.id_internaute
WHERE R.id_recette = 3;
