----------------------------------------------------------------------------------------------
--Ne pas s'inscrire avec un pseudo déjà existant
--

DELIMITER //
CREATE TRIGGER VERIF_PSEUDO 
AFTER INSERT ON Internaute 
FOR EACH ROW 
WHEN ((SELECT COUNT(pseudo) FROM Internaute WHERE pseudo=NEW.pseudo GROUP BY pseudo)>1) 
BEGIN
	DELETE FROM Internaute WHERE id_internaute = NEW.id_internaute; 
END;
//




