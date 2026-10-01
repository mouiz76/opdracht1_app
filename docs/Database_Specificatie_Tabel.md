# Database Specificatie Tabellen - Magazijn Jamin

## Overzicht van Database Tabellen

Het systeem maakt gebruik van **1 database** waarin alle 6 specificatietabellen en hun onderlinge relaties zijn gedefinieerd.

---

### 1. Tabel: `Product`
Stamtabel voor alle producten in het assortiment.

| Veldnaam | Datatype | Nullable | Primary/Foreign Key | Omschrijving |
|---|---|---|---|---|
| `Id` | `TINYINT UNSIGNED` | NOT NULL | **PK** (Auto Increment) | Uniek ID van het product |
| `Naam` | `VARCHAR(50)` | NOT NULL | - | Productnaam |
| `Barcode` | `VARCHAR(13)` | NOT NULL | - | EAN/Barcode nummer van het product |
| `IsActief` | `BIT` | NOT NULL (Default: 1) | - | Status van het record (systeemveld) |
| `Opmerking` | `VARCHAR(250)` | NULL | - | Eventuele opmerking (systeemveld) |
| `DatumAangemaakt` | `DateTime(6)` | NOT NULL | - | Aanmaakdatum (systeemveld) |
| `DatumGewijzigd` | `DateTime(6)` | NOT NULL | - | Laatste wijzigingsdatum (systeemveld) |

---

### 2. Tabel: `Leverancier`
Stamtabel voor de leveranciers.

| Veldnaam | Datatype | Nullable | Primary/Foreign Key | Omschrijving |
|---|---|---|---|---|
| `Id` | `TINYINT UNSIGNED` | NOT NULL | **PK** (Auto Increment) | Uniek ID van de leverancier |
| `Naam` | `VARCHAR(50)` | NOT NULL | - | Bedrijfsnaam leverancier |
| `ContactPersoon` | `VARCHAR(50)` | NOT NULL | - | Naam contactpersoon leverancier |
| `LeverancierNummer` | `VARCHAR(15)` | NOT NULL | - | Uniek registratienummer leverancier |
| `Mobiel` | `VARCHAR(12)` | NOT NULL | - | Telefoonnummer van de leverancier |
| `IsActief` | `BIT` | NOT NULL (Default: 1) | - | Status van het record (systeemveld) |
| `Opmerking` | `VARCHAR(250)` | NULL | - | Eventuele opmerking (systeemveld) |
| `DatumAangemaakt` | `DateTime(6)` | NOT NULL | - | Aanmaakdatum (systeemveld) |
| `DatumGewijzigd` | `DateTime(6)` | NOT NULL | - | Laatste wijzigingsdatum (systeemveld) |

---

### 3. Tabel: `Allergeen`
Stamtabel voor de verschillende allergenen.

| Veldnaam | Datatype | Nullable | Primary/Foreign Key | Omschrijving |
|---|---|---|---|---|
| `Id` | `TINYINT UNSIGNED` | NOT NULL | **PK** (Auto Increment) | Uniek ID van het allergeen |
| `Naam` | `VARCHAR(30)` | NOT NULL | - | Naam van het allergeen |
| `Omschrijving` | `VARCHAR(100)` | NOT NULL | - | Omschrijving/waarschuwing van allergeen |
| `IsActief` | `BIT` | NOT NULL (Default: 1) | - | Status van het record (systeemveld) |
| `Opmerking` | `VARCHAR(250)` | NULL | - | Eventuele opmerking (systeemveld) |
| `DatumAangemaakt` | `DateTime(6)` | NOT NULL | - | Aanmaakdatum (systeemveld) |
| `DatumGewijzigd` | `DateTime(6)` | NOT NULL | - | Laatste wijzigingsdatum (systeemveld) |

---

### 4. Tabel: `Magazijn`
Koppeltabel/Voorraadtabel voor producten in het magazijn.

| Veldnaam | Datatype | Nullable | Primary/Foreign Key | Omschrijving |
|---|---|---|---|---|
| `Id` | `TINYINT UNSIGNED` | NOT NULL | **PK** (Auto Increment) | Uniek ID van het magazijnrecord |
| `ProductId` | `TINYINT UNSIGNED` | NOT NULL | **FK** -> `Product(Id)` | Referentie naar het product |
| `VerpakkingsEenheidInKg` | `DECIMAL(4,1)` | NOT NULL | - | Verpakkingseenheid in kg |
| `AantalAanwezig` | `SMALLINT UNSIGNED` | NULL | - | Aantal stuks op voorraad (NULL = geen voorraad) |
| `IsActief` | `BIT` | NOT NULL (Default: 1) | - | Status van het record (systeemveld) |
| `Opmerking` | `VARCHAR(250)` | NULL | - | Eventuele opmerking (systeemveld) |
| `DatumAangemaakt` | `DateTime(6)` | NOT NULL | - | Aanmaakdatum (systeemveld) |
| `DatumGewijzigd` | `DateTime(6)` | NOT NULL | - | Laatste wijzigingsdatum (systeemveld) |

---

### 5. Tabel: `ProductPerAllergeen`
Koppeltabel tussen `Product` en `Allergeen` (Veel-op-Veel relatie).

| Veldnaam | Datatype | Nullable | Primary/Foreign Key | Omschrijving |
|---|---|---|---|---|
| `Id` | `TINYINT UNSIGNED` | NOT NULL | **PK** (Auto Increment) | Uniek ID van de koppeling |
| `ProductId` | `TINYINT UNSIGNED` | NOT NULL | **FK** -> `Product(Id)` | Referentie naar het product |
| `AllergeenId` | `TINYINT UNSIGNED` | NOT NULL | **FK** -> `Allergeen(Id)` | Referentie naar het allergeen |
| `IsActief` | `BIT` | NOT NULL (Default: 1) | - | Status van het record (systeemveld) |
| `Opmerking` | `VARCHAR(250)` | NULL | - | Eventuele opmerking (systeemveld) |
| `DatumAangemaakt` | `DateTime(6)` | NOT NULL | - | Aanmaakdatum (systeemveld) |
| `DatumGewijzigd` | `DateTime(6)` | NOT NULL | - | Laatste wijzigingsdatum (systeemveld) |

---

### 6. Tabel: `ProductPerLeverancier`
Koppeltabel tussen `Leverancier` en `Product` voor het vastleggen van leveringen.

| Veldnaam | Datatype | Nullable | Primary/Foreign Key | Omschrijving |
|---|---|---|---|---|
| `Id` | `TINYINT UNSIGNED` | NOT NULL | **PK** (Auto Increment) | Uniek ID van het leveringsrecord |
| `LeverancierId` | `TINYINT UNSIGNED` | NOT NULL | **FK** -> `Leverancier(Id)` | Referentie naar de leverancier |
| `ProductId` | `TINYINT UNSIGNED` | NOT NULL | **FK** -> `Product(Id)` | Referentie naar het product |
| `DatumLevering` | `DATE` | NOT NULL | - | Datum waarop levering heeft plaatsgevonden |
| `Aantal` | `SMALLINT UNSIGNED` | NOT NULL | - | Geleverd aantal stuks |
| `DatumEerstVolgendeLevering` | `DATE` | NULL | - | Datum verwachte eerstvolgende levering |
| `IsActief` | `BIT` | NOT NULL (Default: 1) | - | Status van het record (systeemveld) |
| `Opmerking` | `VARCHAR(250)` | NULL | - | Eventuele opmerking (systeemveld) |
| `DatumAangemaakt` | `DateTime(6)` | NOT NULL | - | Aanmaakdatum (systeemveld) |
| `DatumGewijzigd` | `DateTime(6)` | NOT NULL | - | Laatste wijzigingsdatum (systeemveld) |
