<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Voer de database-aanpassingen uit voor Opdracht 2.
     * - Voeg leverancier Quality Street toe (Id = 6)
     * - Zet Winegums (Id = 10) op IsActief = 0
     * - Maak Stored Procedures aan voor de vier scenario's
     */
    public function up(): void
    {
        $isSqlite = DB::getDriverName() === 'sqlite';

        // 1. Voeg Quality Street toe als hij nog niet bestaat en zet Winegums inactief voor SQLite
        if ($isSqlite) {
            DB::statement("
                INSERT INTO Leverancier (Id, Naam, ContactPersoon, LeverancierNummer, Mobiel, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd)
                SELECT 6, 'Quality Street', 'Johan Nooij', 'L1029234586', '06-23458456', 1, NULL, datetime('now'), datetime('now')
                WHERE NOT EXISTS (SELECT 1 FROM Leverancier WHERE Id = 6)
            ");

            DB::statement("
                UPDATE Product SET IsActief = 0, DatumGewijzigd = datetime('now')
                WHERE Id = 10
            ");

            return;
        }

        // MySQL implementatie
        // 1. Voeg Quality Street toe als hij nog niet bestaat
        DB::statement("
            INSERT INTO Leverancier (Id, Naam, ContactPersoon, LeverancierNummer, Mobiel, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd)
            SELECT 6, 'Quality Street', 'Johan Nooij', 'L1029234586', '06-23458456', 1, NULL, SYSDATE(6), SYSDATE(6)
            WHERE NOT EXISTS (SELECT 1 FROM Leverancier WHERE Id = 6)
        ");

        // 2. Zet Winegums (Product.Id = 10) op IsActief = 0
        DB::statement("
            UPDATE Product SET IsActief = 0, DatumGewijzigd = SYSDATE(6)
            WHERE Id = 10
        ");

        // 3. Stored Procedure: Overzicht leveranciers met aantal verschillende producten
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_GetLeveranciersOverzicht");
        DB::unprepared("
            CREATE PROCEDURE sp_GetLeveranciersOverzicht()
            BEGIN
                -- Haal alle leveranciers op met het aantal VERSCHILLENDE producten
                -- Leveranciers worden gesorteerd op aantal producten (aflopend), daarna alfabetisch
                SELECT
                    l.Id,
                    l.Naam,
                    l.ContactPersoon,
                    l.LeverancierNummer,
                    l.Mobiel,
                    COUNT(DISTINCT ppl.ProductId) AS AantalProducten
                FROM Leverancier l
                LEFT JOIN ProductPerLeverancier ppl ON ppl.LeverancierId = l.Id
                GROUP BY l.Id, l.Naam, l.ContactPersoon, l.LeverancierNummer, l.Mobiel
                ORDER BY AantalProducten DESC, l.Naam ASC;
            END
        ");

        // 4. Stored Procedure: Geleverde producten van één leverancier
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_GetGeleverdeProducten");
        DB::unprepared("
            CREATE PROCEDURE sp_GetGeleverdeProducten(IN p_LeverancierId TINYINT UNSIGNED)
            BEGIN
                -- Haal de producten op van één leverancier
                -- Gebruik MAX(DatumLevering) zodat elk product maar één keer verschijnt
                -- Sorteer op AantalAanwezig aflopend
                SELECT
                    p.Id AS ProductId,
                    p.Naam AS ProductNaam,
                    p.Barcode,
                    p.IsActief,
                    m.VerpakkingsEenheidInKg,
                    m.AantalAanwezig,
                    MAX(ppl.DatumLevering) AS LaatsteLeverdatum,
                    MAX(ppl.DatumLevering) AS LaatsteLeverbatum
                FROM ProductPerLeverancier ppl
                INNER JOIN Product p ON p.Id = ppl.ProductId
                INNER JOIN Magazijn m ON m.ProductId = p.Id
                WHERE ppl.LeverancierId = p_LeverancierId
                GROUP BY p.Id, p.Naam, p.Barcode, p.IsActief, m.VerpakkingsEenheidInKg, m.AantalAanwezig
                ORDER BY m.AantalAanwezig DESC;
            END
        ");

        // 5. Stored Procedure: Productgegevens voor het leveringsformulier
        // Controleer of het product daadwerkelijk door de geselecteerde leverancier wordt geleverd
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_GetLeveringProduct");
        DB::unprepared("
            CREATE PROCEDURE sp_GetLeveringProduct(
                IN p_ProductId TINYINT UNSIGNED,
                IN p_LeverancierId TINYINT UNSIGNED
            )
            BEGIN
                -- Haal productgegevens op voor het leveringsformulier
                -- Controleer expliciet via ProductPerLeverancier of de leverancier dit product levert
                SELECT
                    p.Id AS ProductId,
                    p.Naam AS ProductNaam,
                    p.Barcode,
                    p.IsActief,
                    l.Id AS LeverancierId,
                    l.Naam AS LeverancierNaam,
                    l.ContactPersoon,
                    l.LeverancierNummer,
                    l.Mobiel,
                    m.VerpakkingsEenheidInKg,
                    m.AantalAanwezig
                FROM Product p
                INNER JOIN ProductPerLeverancier ppl ON ppl.ProductId = p.Id AND ppl.LeverancierId = p_LeverancierId
                INNER JOIN Leverancier l ON l.Id = ppl.LeverancierId
                INNER JOIN Magazijn m ON m.ProductId = p.Id
                WHERE p.Id = p_ProductId
                LIMIT 1;
            END
        ");

        // 6. Stored Procedure: Voeg nieuwe levering toe (in veilige transactie)
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_VoegProductLeveringToe");
        DB::unprepared("
            CREATE PROCEDURE sp_VoegProductLeveringToe(
                IN p_LeverancierId TINYINT UNSIGNED,
                IN p_ProductId TINYINT UNSIGNED,
                IN p_Aantal SMALLINT UNSIGNED,
                IN p_DatumEerstVolgendeLevering DATE,
                OUT p_ResultCode TINYINT,
                OUT p_ResultMessage VARCHAR(255)
            )
            BEGIN
                DECLARE v_IsActief BIT;
                DECLARE v_HeeftLeverancier INT;

                -- Foutafhandeling: rollback bij een fout
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    SET p_ResultCode = -1;
                    SET p_ResultMessage = 'Er is een databasefout opgetreden';
                END;

                -- Stap 1: Valideer aantal
                IF p_Aantal IS NULL OR p_Aantal <= 0 THEN
                    SET p_ResultCode = 4;
                    SET p_ResultMessage = 'Aantal moet groter zijn dan 0';
                ELSE
                    -- Stap 2: Controleer of het product bestaat en actief is
                    SELECT IsActief INTO v_IsActief
                    FROM Product
                    WHERE Id = p_ProductId
                    LIMIT 1;

                    IF v_IsActief IS NULL THEN
                        SET p_ResultCode = 2;
                        SET p_ResultMessage = 'Product niet gevonden';
                    ELSEIF v_IsActief = 0 THEN
                        -- Product is inactief: levering mag NIET worden opgeslagen
                        SET p_ResultCode = 1;
                        SET p_ResultMessage = 'Product is inactief';
                    ELSE
                        -- Stap 3: Controleer of de leverancier dit product levert
                        SELECT COUNT(*) INTO v_HeeftLeverancier
                        FROM ProductPerLeverancier
                        WHERE LeverancierId = p_LeverancierId AND ProductId = p_ProductId;

                        IF v_HeeftLeverancier = 0 THEN
                            SET p_ResultCode = 3;
                            SET p_ResultMessage = 'Leverancier levert dit product niet';
                        ELSE
                            -- Stap 4: Alles klopt, voer de transactie uit
                            START TRANSACTION;

                            -- Voeg de nieuwe levering toe aan ProductPerLeverancier
                            INSERT INTO ProductPerLeverancier
                                (LeverancierId, ProductId, DatumLevering, Aantal, DatumEerstVolgendeLevering, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd)
                            VALUES
                                (p_LeverancierId, p_ProductId, CURDATE(), p_Aantal, p_DatumEerstVolgendeLevering, 1, NULL, SYSDATE(6), SYSDATE(6));

                            -- Verhoog de voorraad in het Magazijn
                            UPDATE Magazijn
                            SET AantalAanwezig = COALESCE(AantalAanwezig, 0) + p_Aantal,
                                DatumGewijzigd = SYSDATE(6)
                            WHERE ProductId = p_ProductId;

                            COMMIT;

                            SET p_ResultCode = 0;
                            SET p_ResultMessage = 'Levering succesvol toegevoegd';
                        END IF;
                    END IF;
                END IF;
            END
        ");
    }

    /**
     * Draai de aanpassingen terug.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::unprepared("DROP PROCEDURE IF EXISTS sp_VoegProductLeveringToe");
            DB::unprepared("DROP PROCEDURE IF EXISTS sp_GetLeveringProduct");
            DB::unprepared("DROP PROCEDURE IF EXISTS sp_GetGeleverdeProducten");
            DB::unprepared("DROP PROCEDURE IF EXISTS sp_GetLeveranciersOverzicht");
        }

        // Verwijder Quality Street
        DB::statement("DELETE FROM Leverancier WHERE Id = 6");

        // Zet Winegums terug op actief
        DB::statement("UPDATE Product SET IsActief = 1 WHERE Id = 10");
    }
};
