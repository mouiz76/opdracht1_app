-- Jamin Opdracht 3 - importeer in een leeg MySQL-schema.
-- Zeven domeintabellen, testdata, relaties en procedures voor opdrachten 2 en 3.

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Product` (
  `Id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `Naam` varchar(50) NOT NULL,
  `Barcode` varchar(13) NOT NULL,
  `IsActief` bit(1) NOT NULL DEFAULT b'1',
  `Opmerking` varchar(250) DEFAULT NULL,
  `DatumAangemaakt` datetime(6) NOT NULL,
  `DatumGewijzigd` datetime(6) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `Product` VALUES (1,'Mintnopjes','8719587231278',b'1',NULL,'2026-11-18 10:51:07.905476','2026-11-18 10:51:07.905480'),(2,'Schoolkrijt','8719587326713',b'1',NULL,'2026-11-18 10:51:07.905786','2026-11-18 10:51:07.905787'),(3,'Honingdrop','8719587327836',b'1',NULL,'2026-11-18 10:51:07.905800','2026-11-18 10:51:07.905800'),(4,'Zure Beren','8719587321441',b'1',NULL,'2026-11-18 10:51:07.905804','2026-11-18 10:51:07.905804'),(5,'Cola Flesjes','8719587321237',b'1',NULL,'2026-11-18 10:51:07.905806','2026-11-18 10:51:07.905807'),(6,'Turtles','8719587322245',b'1',NULL,'2026-11-18 10:51:07.905809','2026-11-18 10:51:07.905810'),(7,'Witte Muizen','8719587328256',b'1',NULL,'2026-11-18 10:51:07.905812','2026-11-18 10:51:07.905812'),(8,'Reuzen Slangen','8719587325641',b'1',NULL,'2026-11-18 10:51:07.905815','2026-11-18 10:51:07.905815'),(9,'Zoute Rijen','8719587322739',b'1',NULL,'2026-11-18 10:51:07.905818','2026-11-18 10:51:07.905818'),(10,'Winegums','8719587327527',b'0',NULL,'2026-11-18 10:51:07.905820','2026-11-18 10:51:08.042350'),(11,'Drop Munten','8719587322345',b'1',NULL,'2026-11-18 10:51:07.905823','2026-11-18 10:51:07.905823'),(12,'Kruis Drop','8719587322265',b'1',NULL,'2026-11-18 10:51:07.905826','2026-11-18 10:51:07.905826'),(13,'Zoute Ruitjes','8719587323256',b'1',NULL,'2026-11-18 10:51:07.905829','2026-11-18 10:51:07.905829');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Leverancier` (
  `Id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `Naam` varchar(50) NOT NULL,
  `ContactPersoon` varchar(50) NOT NULL,
  `LeverancierNummer` varchar(15) NOT NULL,
  `Mobiel` varchar(12) NOT NULL,
  `IsActief` bit(1) NOT NULL DEFAULT b'1',
  `Opmerking` varchar(250) DEFAULT NULL,
  `DatumAangemaakt` datetime(6) NOT NULL,
  `DatumGewijzigd` datetime(6) NOT NULL,
  `ContactId` int unsigned DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `ContactId` (`ContactId`),
  CONSTRAINT `FK_Leverancier_Contact` FOREIGN KEY (`ContactId`) REFERENCES `Contact` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `Leverancier` VALUES (1,'Venco','Bert van Linge','L1029384719','06-28493827',b'1',NULL,'2026-11-18 10:51:07.921867','2026-11-18 10:51:07.921870',1),(2,'Astra Sweets','Jasper del Monte','L1029284315','06-39398734',b'1',NULL,'2026-11-18 10:51:07.922199','2026-11-18 10:51:07.922201',2),(3,'Haribo','Sven Stalman','L1029324748','06-24383291',b'1',NULL,'2026-11-18 10:51:07.922213','2026-11-18 10:51:07.922213',3),(4,'Basset','Joyce Stelterberg','L1023845773','06-48293823',b'1',NULL,'2026-11-18 10:51:07.922217','2026-11-18 10:51:07.922217',4),(5,'De Bron','Remco Veenstra','L1023857736','06-34291234',b'1',NULL,'2026-11-18 10:51:07.922220','2026-11-18 10:51:07.922220',5),(6,'Quality Street','Johan Nooij','L1029234586','06-23458456',b'1',NULL,'2026-11-18 10:51:08.038431','2026-11-18 10:51:08.038435',6);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Contact` (
  `Id` int unsigned NOT NULL AUTO_INCREMENT,
  `Straat` varchar(50) NOT NULL,
  `Huisnummer` int unsigned NOT NULL,
  `Postcode` varchar(10) NOT NULL,
  `Stad` varchar(50) NOT NULL,
  `IsActief` bit(1) NOT NULL DEFAULT b'1',
  `Opmerking` varchar(250) DEFAULT NULL,
  `DatumAangemaakt` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `DatumGewijzigd` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `Contact` VALUES (1,'Van Gilslaan',34,'1045CB','Hilvarenbeek',b'1',NULL,'2026-11-18 10:51:08.072375','2026-11-18 10:51:08.072375'),(2,'Den Dolderpad',2,'1067RC','Utrecht',b'1',NULL,'2026-11-18 10:51:08.072375','2026-11-18 10:51:08.072375'),(3,'Fredo Raalteweg',257,'1236OP','Nijmegen',b'1',NULL,'2026-11-18 10:51:08.072375','2026-11-18 10:51:08.072375'),(4,'Bertrand Russellhof',21,'2034AP','Den Haag',b'1',NULL,'2026-11-18 10:51:08.072375','2026-11-18 10:51:08.072375'),(5,'Leon van Bonstraat',213,'145XC','Lunteren',b'1',NULL,'2026-11-18 10:51:08.072375','2026-11-18 10:51:08.072375'),(6,'Bea van Lingenlaan',234,'2197FG','Sint Pancras',b'1',NULL,'2026-11-18 10:51:08.072375','2026-11-18 10:51:08.072375');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Allergeen` (
  `Id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `Naam` varchar(30) NOT NULL,
  `Omschrijving` varchar(100) NOT NULL,
  `IsActief` bit(1) NOT NULL DEFAULT b'1',
  `Opmerking` varchar(250) DEFAULT NULL,
  `DatumAangemaakt` datetime(6) NOT NULL,
  `DatumGewijzigd` datetime(6) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `Allergeen` VALUES (1,'Gluten','Dit product bevat gluten',b'1',NULL,'2026-11-18 10:51:07.938860','2026-11-18 10:51:07.938864'),(2,'Gelatine','Dit product bevat gelatine',b'1',NULL,'2026-11-18 10:51:07.938922','2026-11-18 10:51:07.938923'),(3,'AZO-Kleurstof','Dit product bevat AZO-kleurstoffen',b'1',NULL,'2026-11-18 10:51:07.938939','2026-11-18 10:51:07.938940'),(4,'Lactose','Dit product bevat lactose',b'1',NULL,'2026-11-18 10:51:07.938944','2026-11-18 10:51:07.938945'),(5,'Soja','Dit product bevat soja',b'1',NULL,'2026-11-18 10:51:07.938948','2026-11-18 10:51:07.938949');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Magazijn` (
  `Id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `ProductId` tinyint unsigned NOT NULL,
  `VerpakkingsEenheidInKg` decimal(4,1) NOT NULL,
  `AantalAanwezig` smallint unsigned DEFAULT NULL,
  `IsActief` bit(1) NOT NULL DEFAULT b'1',
  `Opmerking` varchar(250) DEFAULT NULL,
  `DatumAangemaakt` datetime(6) NOT NULL,
  `DatumGewijzigd` datetime(6) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `FK_Magazijn_ProductId_Product_Id` (`ProductId`),
  CONSTRAINT `FK_Magazijn_ProductId_Product_Id` FOREIGN KEY (`ProductId`) REFERENCES `Product` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `Magazijn` VALUES (1,1,5.0,453,b'1',NULL,'2026-11-18 10:51:07.966753','2026-11-18 10:51:07.966756'),(2,2,2.5,400,b'1',NULL,'2026-11-18 10:51:07.966804','2026-11-18 10:51:07.966804'),(3,3,5.0,1,b'1',NULL,'2026-11-18 10:51:07.966819','2026-11-18 10:51:07.966819'),(4,4,1.0,800,b'1',NULL,'2026-11-18 10:51:07.966824','2026-11-18 10:51:07.966824'),(5,5,3.0,234,b'1',NULL,'2026-11-18 10:51:07.966831','2026-11-18 10:51:07.966831'),(6,6,2.0,345,b'1',NULL,'2026-11-18 10:51:07.966836','2026-11-18 10:51:07.966836'),(7,7,1.0,795,b'1',NULL,'2026-11-18 10:51:07.966840','2026-11-18 10:51:07.966841'),(8,8,10.0,233,b'1',NULL,'2026-11-18 10:51:07.966845','2026-11-18 10:51:07.966845'),(9,9,2.5,123,b'1',NULL,'2026-11-18 10:51:07.966849','2026-11-18 10:51:07.966849'),(10,10,3.0,NULL,b'1',NULL,'2026-11-18 10:51:07.966853','2026-11-18 10:51:07.966853'),(11,11,2.0,367,b'1',NULL,'2026-11-18 10:51:07.966857','2026-11-18 10:51:07.966858'),(12,12,1.0,467,b'1',NULL,'2026-11-18 10:51:07.966862','2026-11-18 10:51:07.966862'),(13,13,5.0,20,b'1',NULL,'2026-11-18 10:51:07.966866','2026-11-18 10:51:07.966866');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ProductPerAllergeen` (
  `Id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `ProductId` tinyint unsigned NOT NULL,
  `AllergeenId` tinyint unsigned NOT NULL,
  `IsActief` bit(1) NOT NULL DEFAULT b'1',
  `Opmerking` varchar(250) DEFAULT NULL,
  `DatumAangemaakt` datetime(6) NOT NULL,
  `DatumGewijzigd` datetime(6) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `FK_ProductPerAllergeen_ProductId_Product_Id` (`ProductId`),
  KEY `FK_ProductPerAllergeen_AllergeenId_Allergeen_Id` (`AllergeenId`),
  CONSTRAINT `FK_ProductPerAllergeen_AllergeenId_Allergeen_Id` FOREIGN KEY (`AllergeenId`) REFERENCES `Allergeen` (`Id`),
  CONSTRAINT `FK_ProductPerAllergeen_ProductId_Product_Id` FOREIGN KEY (`ProductId`) REFERENCES `Product` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `ProductPerAllergeen` VALUES (1,1,2,b'1',NULL,'2026-11-18 10:51:07.999440','2026-11-18 10:51:07.999443'),(2,1,1,b'1',NULL,'2026-11-18 10:51:07.999491','2026-11-18 10:51:07.999492'),(3,1,3,b'1',NULL,'2026-11-18 10:51:07.999508','2026-11-18 10:51:07.999508'),(4,3,4,b'1',NULL,'2026-11-18 10:51:07.999515','2026-11-18 10:51:07.999515'),(5,6,5,b'1',NULL,'2026-11-18 10:51:07.999521','2026-11-18 10:51:07.999521'),(6,9,2,b'1',NULL,'2026-11-18 10:51:07.999527','2026-11-18 10:51:07.999527'),(7,9,5,b'1',NULL,'2026-11-18 10:51:07.999533','2026-11-18 10:51:07.999533'),(8,10,2,b'1',NULL,'2026-11-18 10:51:07.999539','2026-11-18 10:51:07.999539'),(9,12,4,b'1',NULL,'2026-11-18 10:51:07.999545','2026-11-18 10:51:07.999545'),(10,13,1,b'1',NULL,'2026-11-18 10:51:07.999551','2026-11-18 10:51:07.999551'),(11,13,4,b'1',NULL,'2026-11-18 10:51:07.999556','2026-11-18 10:51:07.999556'),(12,13,5,b'1',NULL,'2026-11-18 10:51:07.999562','2026-11-18 10:51:07.999562');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ProductPerLeverancier` (
  `Id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `LeverancierId` tinyint unsigned NOT NULL,
  `ProductId` tinyint unsigned NOT NULL,
  `DatumLevering` date NOT NULL,
  `Aantal` smallint unsigned NOT NULL,
  `DatumEerstVolgendeLevering` date DEFAULT NULL,
  `IsActief` bit(1) NOT NULL DEFAULT b'1',
  `Opmerking` varchar(250) DEFAULT NULL,
  `DatumAangemaakt` datetime(6) NOT NULL,
  `DatumGewijzigd` datetime(6) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `FK_ProductPerLeverancier_LeverancierId_Leverancier_Id` (`LeverancierId`),
  KEY `FK_ProductPerLeverancier_ProductId_Product_Id` (`ProductId`),
  CONSTRAINT `FK_ProductPerLeverancier_LeverancierId_Leverancier_Id` FOREIGN KEY (`LeverancierId`) REFERENCES `Leverancier` (`Id`),
  CONSTRAINT `FK_ProductPerLeverancier_ProductId_Product_Id` FOREIGN KEY (`ProductId`) REFERENCES `Product` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `ProductPerLeverancier` VALUES (1,1,1,'2024-10-09',23,'2024-10-16',b'1',NULL,'2026-11-18 10:51:08.032450','2026-11-18 10:51:08.032452'),(2,1,1,'2024-10-18',21,'2024-10-25',b'1',NULL,'2026-11-18 10:51:08.032503','2026-11-18 10:51:08.032503'),(3,1,2,'2024-10-09',12,'2024-10-16',b'1',NULL,'2026-11-18 10:51:08.032519','2026-11-18 10:51:08.032520'),(4,1,3,'2024-10-10',11,'2024-10-17',b'1',NULL,'2026-11-18 10:51:08.032527','2026-11-18 10:51:08.032527'),(5,2,4,'2024-10-14',16,'2024-10-21',b'1',NULL,'2026-11-18 10:51:08.032533','2026-11-18 10:51:08.032533'),(6,2,4,'2024-10-21',23,'2024-10-28',b'1',NULL,'2026-11-18 10:51:08.032540','2026-11-18 10:51:08.032540'),(7,2,5,'2024-10-14',45,'2024-10-21',b'1',NULL,'2026-11-18 10:51:08.032546','2026-11-18 10:51:08.032546'),(8,2,6,'2024-10-14',30,'2024-10-21',b'1',NULL,'2026-11-18 10:51:08.032552','2026-11-18 10:51:08.032552'),(9,3,7,'2024-10-12',12,'2024-10-19',b'1',NULL,'2026-11-18 10:51:08.032559','2026-11-18 10:51:08.032559'),(10,3,7,'2024-10-19',23,'2024-10-26',b'1',NULL,'2026-11-18 10:51:08.032565','2026-11-18 10:51:08.032565'),(11,3,8,'2024-10-10',12,'2024-10-17',b'1',NULL,'2026-11-18 10:51:08.032571','2026-11-18 10:51:08.032571'),(12,3,9,'2024-10-11',1,'2024-10-18',b'1',NULL,'2026-11-18 10:51:08.032577','2026-11-18 10:51:08.032578'),(13,4,10,'2024-10-16',24,'2024-10-30',b'1',NULL,'2026-11-18 10:51:08.032584','2026-11-18 10:51:08.032584'),(14,5,11,'2024-10-10',47,'2024-10-17',b'1',NULL,'2026-11-18 10:51:08.032590','2026-11-18 10:51:08.032590'),(15,5,11,'2024-10-19',60,'2024-10-26',b'1',NULL,'2026-11-18 10:51:08.032596','2026-11-18 10:51:08.032596'),(16,5,12,'2024-10-11',45,NULL,b'1',NULL,'2026-11-18 10:51:08.032601','2026-11-18 10:51:08.032601'),(17,5,13,'2024-10-12',23,NULL,b'1',NULL,'2026-11-18 10:51:08.032607','2026-11-18 10:51:08.032607');
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_GetGeleverdeProducten`(IN p_LeverancierId TINYINT UNSIGNED)
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
            END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_GetLeverancierDetails`(IN p_Id INT UNSIGNED)
BEGIN
    SELECT l.Id, l.Naam, l.ContactPersoon, l.LeverancierNummer, l.Mobiel,
           c.Straat, c.Huisnummer, c.Postcode, c.Stad
    FROM Leverancier l
    LEFT JOIN Contact c ON c.Id = l.ContactId
    WHERE l.Id = p_Id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_GetLeveranciersOverzicht`()
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
            END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_GetLeveranciersPagina`(IN p_Offset INT UNSIGNED)
BEGIN
    SELECT Id, Naam, ContactPersoon, LeverancierNummer, Mobiel
    FROM Leverancier ORDER BY Id LIMIT 4 OFFSET p_Offset;
    SELECT COUNT(*) AS Totaal FROM Leverancier;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_GetLeveringProduct`(
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
            END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_VoegProductLeveringToe`(
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
            END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE PROCEDURE `sp_WijzigLeverancier`(
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
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
