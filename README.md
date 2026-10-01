# BE-opdracht 01 - Magazijn Jamin Webapplicatie

Webapplicatie ontwikkeld volgens het **MVC-framework (Laravel)**, **OOP** en **PDO** voor Jamin Magazijn beheer.

## Project Informatie
- **Vak:** Backend Programming (BE-opdracht 01)
- **Onderwerp:** Realiseren van User Story 01 & User Story 02

---

## Functionaliteiten & User Stories

### User Story 01: Inzien leveringsinformatie product
- **Scenario 01:** Bekijken van de leveringsinformatie van een gekozen product (bijv. Mintnopjes) inclusief leveranciersgegevens, geleverde aantallen en verwachte leverdata, gesorteerd op `DatumLevering` (Datum laatste levering) oplopend.
- **Scenario 02:** Melding tonen bij producten zonder voorraad (bijv. Winegums): *"Er is van dit product op dit moment geen voorraad aanwezig, de verwachte eerstvolgende levering is: 30-04-2023"* met een automatische redirect na 4 seconden naar de overzichtspagina.

### User Story 02: Inzien allergeneninformatie product
- **Scenario 01:** Bekijken van het allergenenoverzicht van een gekozen product (bijv. Zoute Ruitjes) inclusief allergenennaam en omschrijving, gesorteerd op `Naam` oplopend.
- **Scenario 02:** Melding tonen bij producten zonder allergenen (bijv. Cola Flesjes): *"In dit product zitten geen stoffen die een allergische reactie kunnen veroorzaken"* met een automatische redirect na 4 seconden naar de overzichtspagina.

---

## Database Structuur
Het project maakt gebruik van **1 centrale database** met 6 specificatietabellen:
1. `Product` (Stamtabel producten)
2. `Leverancier` (Stamtabel leveranciers)
3. `Allergeen` (Stamtabel allergenen)
4. `Magazijn` (Koppeltabel/voorraadtabel)
5. `ProductPerAllergeen` (Koppeltabel product - allergeen)
6. `ProductPerLeverancier` (Koppeltabel product - leverancier)

Alle tabellen bevatten de verplichte systeemvelden: `IsActief` (BIT), `Opmerking` (VARCHAR(250)), `DatumAangemaakt` (DateTime(6)) en `DatumGewijzigd` (DateTime(6)).

---

## Project Mappen & Inlevering
- `db/` - Bevat de SQL export `db_jamin.sql`
- `docs/` - Bevat de documentatie `Database_Specificatie_Tabel.md`
- `vids/` - Bevat de video-demonstratie (max 60 sec) van de browser scenario's en phpMyAdmin

---

## Installatie & Uitvoeren
1. Kloon de repository
2. Installeer dependencies: `composer install` & `npm install`
3. Configureer `.env` voor MySQL/MariaDB database
4. Voer migraties uit: `php artisan migrate`
5. Start de dev-server: `php artisan serve`
