DELIMITER $$

CREATE PROCEDURE sp_GetLeveranciersPagina(IN p_Offset INT UNSIGNED)
BEGIN
    SELECT Id, Naam, ContactPersoon, LeverancierNummer, Mobiel
    FROM Leverancier ORDER BY Id LIMIT 4 OFFSET p_Offset;
    SELECT COUNT(*) AS Totaal FROM Leverancier;
END$$

CREATE PROCEDURE sp_GetLeverancierDetails(IN p_Id INT UNSIGNED)
BEGIN
    SELECT l.Id, l.Naam, l.ContactPersoon, l.LeverancierNummer, l.Mobiel,
           c.Straat, c.Huisnummer, c.Postcode, c.Stad
    FROM Leverancier l
    LEFT JOIN Contact c ON c.Id = l.ContactId
    WHERE l.Id = p_Id;
END$$

CREATE PROCEDURE sp_WijzigLeverancier(
    IN p_Id INT UNSIGNED, IN p_Naam VARCHAR(50), IN p_ContactPersoon VARCHAR(50),
    IN p_LeverancierNummer VARCHAR(15), IN p_Mobiel VARCHAR(12),
    IN p_Straat VARCHAR(50), IN p_Huisnummer INT UNSIGNED,
    IN p_Postcode VARCHAR(10), IN p_Stad VARCHAR(50), IN p_SimuleerStoring BOOLEAN
)
BEGIN
    DECLARE v_ContactId INT UNSIGNED;
    DECLARE v_Gevonden BOOLEAN DEFAULT TRUE;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_Gevonden = FALSE;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;
    SELECT ContactId INTO v_ContactId FROM Leverancier WHERE Id = p_Id FOR UPDATE;
    IF NOT v_Gevonden THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Leverancier bestaat niet';
    END IF;

    IF v_ContactId IS NULL THEN
        INSERT INTO Contact (Straat, Huisnummer, Postcode, Stad)
        VALUES (p_Straat, p_Huisnummer, p_Postcode, p_Stad);
        SET v_ContactId = LAST_INSERT_ID();
    ELSE
        UPDATE Contact SET Straat = p_Straat, Huisnummer = p_Huisnummer,
            Postcode = p_Postcode, Stad = p_Stad, DatumGewijzigd = CURRENT_TIMESTAMP(6)
        WHERE Id = v_ContactId;
    END IF;

    UPDATE Leverancier SET Naam = p_Naam, ContactPersoon = p_ContactPersoon,
        LeverancierNummer = p_LeverancierNummer, Mobiel = p_Mobiel,
        ContactId = v_ContactId, DatumGewijzigd = CURRENT_TIMESTAMP(6)
    WHERE Id = p_Id;

    IF p_SimuleerStoring THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gesimuleerde opslagstoring';
    END IF;

    COMMIT;
END$$

DELIMITER ;
