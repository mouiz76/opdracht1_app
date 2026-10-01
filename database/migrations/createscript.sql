-- Step: 01
-- Goal: Create a new database jamin_magazijn
DROP DATABASE IF EXISTS `jamin_magazijn`;
CREATE DATABASE IF NOT EXISTS `jamin_magazijn`;
USE `jamin_magazijn`;


-- Step: 02
-- Goal: Create stamtabel Product
DROP TABLE IF EXISTS Product;

CREATE TABLE IF NOT EXISTS Product
(
    Id                  TINYINT         UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,Naam                VARCHAR(50)                     NOT NULL
   ,Barcode             VARCHAR(13)                     NOT NULL
   ,IsActief            BIT                             NOT NULL    DEFAULT 1
   ,Opmerking           VARCHAR(250)                        NULL    DEFAULT NULL
   ,DatumAangemaakt     DateTime(6)                     NOT NULL
   ,DatumGewijzigd      DateTime(6)                     NOT NULL

   ,CONSTRAINT      PK_Product_Id   PRIMARY KEY CLUSTERED(Id)
) ENGINE=InnoDB;

INSERT INTO Product (Id, Naam, Barcode, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd)
VALUES
     (1, 'Mintnopjes', '8719587231278', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(2, 'Schoolkrijt', '8719587326713', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(3, 'Honingdrop', '8719587327836', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(4, 'Zure Beren', '8719587321441', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(5, 'Cola Flesjes', '8719587321237', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(6, 'Turtles', '8719587322245', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(7, 'Witte Muizen', '8719587328256', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(8, 'Reuzen Slangen', '8719587325641', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(9, 'Zoute Rijen', '8719587322739', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(10, 'Winegums', '8719587327527', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(11, 'Drop Munten', '8719587322345', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(12, 'Kruis Drop', '8719587322265', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(13, 'Zoute Ruitjes', '8719587323256', 1, NULL, SYSDATE(6), SYSDATE(6));


-- Step: 03
-- Goal: Create stamtabel Leverancier
DROP TABLE IF EXISTS Leverancier;

CREATE TABLE IF NOT EXISTS Leverancier
(
    Id                  TINYINT         UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,Naam                VARCHAR(50)                     NOT NULL
   ,ContactPersoon      VARCHAR(50)                     NOT NULL
   ,LeverancierNummer   VARCHAR(15)                     NOT NULL
   ,Mobiel              VARCHAR(12)                     NOT NULL
   ,IsActief            BIT                             NOT NULL    DEFAULT 1
   ,Opmerking           VARCHAR(250)                        NULL    DEFAULT NULL
   ,DatumAangemaakt     DateTime(6)                     NOT NULL
   ,DatumGewijzigd      DateTime(6)                     NOT NULL

   ,CONSTRAINT      PK_Leverancier_Id   PRIMARY KEY CLUSTERED(Id)
) ENGINE=InnoDB;

INSERT INTO Leverancier (Id, Naam, ContactPersoon, LeverancierNummer, Mobiel, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd)
VALUES
     (1, 'Venco', 'Bert van Linge', 'L1029384719', '06-28493827', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(2, 'Astra Sweets', 'Jasper del Monte', 'L1029284315', '06-39398734', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(3, 'Haribo', 'Sven Stalman', 'L1029324748', '06-24383291', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(4, 'Basset', 'Joyce Stelterberg', 'L1023845773', '06-48293823', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(5, 'De Bron', 'Remco Veenstra', 'L1023857736', '06-34291234', 1, NULL, SYSDATE(6), SYSDATE(6));


-- Step: 04
-- Goal: Create stamtabel Allergeen
DROP TABLE IF EXISTS Allergeen;

CREATE TABLE IF NOT EXISTS Allergeen
(
    Id                  TINYINT         UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,Naam                VARCHAR(30)                     NOT NULL
   ,Omschrijving        VARCHAR(100)                    NOT NULL
   ,IsActief            BIT                             NOT NULL    DEFAULT 1
   ,Opmerking           VARCHAR(250)                        NULL    DEFAULT NULL
   ,DatumAangemaakt     DateTime(6)                     NOT NULL
   ,DatumGewijzigd      DateTime(6)                     NOT NULL

   ,CONSTRAINT      PK_Allergeen_Id   PRIMARY KEY CLUSTERED(Id)
) ENGINE=InnoDB;

INSERT INTO Allergeen (Id, Naam, Omschrijving, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd)
VALUES
     (1, 'Gluten', 'Dit product bevat gluten', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(2, 'Gelatine', 'Dit product bevat gelatine', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(3, 'AZO-Kleurstof', 'Dit product bevat AZO-kleurstoffen', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(4, 'Lactose', 'Dit product bevat lactose', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(5, 'Soja', 'Dit product bevat soja', 1, NULL, SYSDATE(6), SYSDATE(6));


-- Step: 05
-- Goal: Create koppeltabel Magazijn
DROP TABLE IF EXISTS Magazijn;

CREATE TABLE IF NOT EXISTS Magazijn
(
    Id                          TINYINT         UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,ProductId                   TINYINT         UNSIGNED        NOT NULL
   ,VerpakkingsEenheidInKg      DECIMAL(4,1)                    NOT NULL
   ,AantalAanwezig              SMALLINT        UNSIGNED            NULL    DEFAULT NULL
   ,IsActief                    BIT                             NOT NULL    DEFAULT 1
   ,Opmerking                   VARCHAR(250)                        NULL    DEFAULT NULL
   ,DatumAangemaakt             DateTime(6)                     NOT NULL
   ,DatumGewijzigd              DateTime(6)                     NOT NULL

   ,CONSTRAINT      PK_Magazijn_Id   PRIMARY KEY CLUSTERED(Id)
   ,CONSTRAINT      FK_Magazijn_ProductId_Product_Id  FOREIGN KEY (ProductId) REFERENCES Product(Id)
) ENGINE=InnoDB;

INSERT INTO Magazijn (Id, ProductId, VerpakkingsEenheidInKg, AantalAanwezig, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd)
VALUES
     (1, 1, 5, 453, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(2, 2, 2.5, 400, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(3, 3, 5, 1, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(4, 4, 1, 800, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(5, 5, 3, 234, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(6, 6, 2, 345, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(7, 7, 1, 795, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(8, 8, 10, 233, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(9, 9, 2.5, 123, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(10, 10, 3, NULL, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(11, 11, 2, 367, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(12, 12, 1, 467, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(13, 13, 5, 20, 1, NULL, SYSDATE(6), SYSDATE(6));


-- Step: 06
-- Goal: Create koppeltabel ProductPerAllergeen
DROP TABLE IF EXISTS ProductPerAllergeen;

CREATE TABLE IF NOT EXISTS ProductPerAllergeen
(
    Id                  TINYINT         UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,ProductId           TINYINT         UNSIGNED        NOT NULL
   ,AllergeenId         TINYINT         UNSIGNED        NOT NULL
   ,IsActief            BIT                             NOT NULL    DEFAULT 1
   ,Opmerking           VARCHAR(250)                        NULL    DEFAULT NULL
   ,DatumAangemaakt     DateTime(6)                     NOT NULL
   ,DatumGewijzigd      DateTime(6)                     NOT NULL

   ,CONSTRAINT      PK_ProductPerAllergeen_Id   PRIMARY KEY CLUSTERED(Id)
   ,CONSTRAINT      FK_ProductPerAllergeen_ProductId_Product_Id  FOREIGN KEY (ProductId) REFERENCES Product(Id)
   ,CONSTRAINT      FK_ProductPerAllergeen_AllergeenId_Allergeen_Id  FOREIGN KEY (AllergeenId) REFERENCES Allergeen(Id)
) ENGINE=InnoDB;

INSERT INTO ProductPerAllergeen (Id, ProductId, AllergeenId, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd)
VALUES
     (1, 1, 2, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(2, 1, 1, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(3, 1, 3, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(4, 3, 4, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(5, 6, 5, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(6, 9, 2, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(7, 9, 5, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(8, 10, 2, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(9, 12, 4, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(10, 13, 1, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(11, 13, 4, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(12, 13, 5, 1, NULL, SYSDATE(6), SYSDATE(6));


-- Step: 07
-- Goal: Create koppeltabel ProductPerLeverancier
DROP TABLE IF EXISTS ProductPerLeverancier;

CREATE TABLE IF NOT EXISTS ProductPerLeverancier
(
    Id                          TINYINT         UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,LeverancierId               TINYINT         UNSIGNED        NOT NULL
   ,ProductId                   TINYINT         UNSIGNED        NOT NULL
   ,DatumLevering               DATE                            NOT NULL
   ,Aantal                      SMALLINT        UNSIGNED        NOT NULL
   ,DatumEerstVolgendeLevering  DATE                                NULL    DEFAULT NULL
   ,IsActief                    BIT                             NOT NULL    DEFAULT 1
   ,Opmerking                   VARCHAR(250)                        NULL    DEFAULT NULL
   ,DatumAangemaakt             DateTime(6)                     NOT NULL
   ,DatumGewijzigd              DateTime(6)                     NOT NULL

   ,CONSTRAINT      PK_ProductPerLeverancier_Id   PRIMARY KEY CLUSTERED(Id)
   ,CONSTRAINT      FK_ProductPerLeverancier_LeverancierId_Leverancier_Id  FOREIGN KEY (LeverancierId) REFERENCES Leverancier(Id)
   ,CONSTRAINT      FK_ProductPerLeverancier_ProductId_Product_Id  FOREIGN KEY (ProductId) REFERENCES Product(Id)
) ENGINE=InnoDB;

INSERT INTO ProductPerLeverancier (Id, LeverancierId, ProductId, DatumLevering, Aantal, DatumEerstVolgendeLevering, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd)
VALUES
     (1, 1, 1, '2024-10-09', 23, '2024-10-16', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(2, 1, 1, '2024-10-18', 21, '2024-10-25', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(3, 1, 2, '2024-10-09', 12, '2024-10-16', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(4, 1, 3, '2024-10-10', 11, '2024-10-17', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(5, 2, 4, '2024-10-14', 16, '2024-10-21', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(6, 2, 4, '2024-10-21', 23, '2024-10-28', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(7, 2, 5, '2024-10-14', 45, '2024-10-21', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(8, 2, 6, '2024-10-14', 30, '2024-10-21', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(9, 3, 7, '2024-10-12', 12, '2024-10-19', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(10, 3, 7, '2024-10-19', 23, '2024-10-26', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(11, 3, 8, '2024-10-10', 12, '2024-10-17', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(12, 3, 9, '2024-10-11', 1, '2024-10-18', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(13, 4, 10, '2024-10-16', 24, '2024-10-30', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(14, 5, 11, '2024-10-10', 47, '2024-10-17', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(15, 5, 11, '2024-10-19', 60, '2024-10-26', 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(16, 5, 12, '2024-10-11', 45, NULL, 1, NULL, SYSDATE(6), SYSDATE(6))
    ,(17, 5, 13, '2024-10-12', 23, NULL, 1, NULL, SYSDATE(6), SYSDATE(6));