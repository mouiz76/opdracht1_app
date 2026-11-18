# Backend Opdracht 3 – Jamin

## Installatie en gebruik

1. Maak met `mysqldump --single-transaction --routines --no-tablespaces` een back-up van je bestaande MySQL-schema. Bewaar de back-up buiten de repository.
2. Start MySQL en voer in het Laravel-project `php artisan migrate` uit.
3. Bouw de frontend met `npm run build` en start Laravel met `php artisan serve`.
4. Log in als manager of met een bestaande beheerder. Open **Wijzigen Leveranciers** op het dashboard.

Gebruik [Create-script-Jamin.sql](Create-script-Jamin.sql) als afzonderlijke
database-export voor een leeg MySQL-schema. Het script bevat de zeven
domeintabellen, testgegevens, sleutels en de procedures van opdrachten 2 en 3.
Laravel maakt bij een volledige installatie ook de eigen frameworktabellen aan.
Het exportbestand bevat uitsluitend domeingegevens uit een vers gemigreerde
testdatabase. Voor de eerdere opdrachten gebruikt de export de bestaande
projectfixtures, waaronder de leveringshistorie, en voor opdracht 3 de zes
contactrecords uit de pdf.

De bronbestanden voor de migratie staan in
[opdracht3-contact.sql](../db/opdracht3-contact.sql) en
[opdracht3-procedures.sql](../db/opdracht3-procedures.sql). Een nieuw
leveranciersrecord zonder contact kan bij de eerste adresopslag een contactrecord
krijgen. De unieke foreign key zorgt dat elk contact aan maximaal één
leverancier gekoppeld is.

## Tests en simulatie

Maak in MySQL een apart schema aan met de naam `jamin_opdracht3_test`. Draai
daarop de migraties, bijvoorbeeld in PowerShell:

```powershell
$env:DB_DATABASE = 'jamin_opdracht3_test'
php artisan migrate
$env:JAMIN_MYSQL_TEST_DATABASE = 'jamin_opdracht3_test'
php vendor/bin/pest
```

De standaardtests gebruiken SQLite. De MySQL-featuretests slaan zichzelf over
als `JAMIN_MYSQL_TEST_DATABASE` ontbreekt. Ze gebruiken de MySQL-host en
inloggegevens uit de lokale Laravel-configuratie.

Voor scenario 2 in de browser start je Laravel met `APP_ENV=testing`,
`DB_DATABASE=jamin_opdracht3_test` en
`JAMIN_SIMULEER_LEVERANCIERSSTORING=true`. Gebruik een manageraccount in dat
testschema. Bij De Bron met mobielnummer `06-39398825` veroorzaakt de procedure
een SQL-fout vlak voor de commit. De foutafhandeling draait alle wijzigingen
terug. Het scenario is servergestuurd.

De [demo](../vids/opdracht3-jamin.mp4) toont de browserstappen, paginering en een
weergave van de MySQL-gegevens en relaties.
