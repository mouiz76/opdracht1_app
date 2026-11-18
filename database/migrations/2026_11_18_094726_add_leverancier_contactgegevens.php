<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const PROCEDURES = ['sp_GetLeveranciersPagina', 'sp_GetLeverancierDetails', 'sp_WijzigLeverancier'];

    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared(file_get_contents(base_path('db/opdracht3-contact.sql')));

        if (! Schema::hasColumn('Leverancier', 'ContactId')) {
            DB::statement('ALTER TABLE Leverancier ADD ContactId INT UNSIGNED NULL UNIQUE,
                ADD CONSTRAINT FK_Leverancier_Contact FOREIGN KEY (ContactId) REFERENCES Contact (Id)');
        }

        DB::statement('UPDATE Leverancier l JOIN Contact c ON c.Id = l.Id
            SET l.ContactId = c.Id WHERE l.ContactId IS NULL AND c.Id BETWEEN 1 AND 6');

        $script = file_get_contents(base_path('db/opdracht3-procedures.sql'));
        $script = preg_replace('/^DELIMITER .*$/m', '', $script);

        foreach (self::PROCEDURES as $procedure) {
            DB::unprepared("DROP PROCEDURE IF EXISTS {$procedure}");
        }

        foreach (array_filter(array_map('trim', explode('$$', $script))) as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach (self::PROCEDURES as $procedure) {
            DB::unprepared("DROP PROCEDURE IF EXISTS {$procedure}");
        }

        DB::statement('ALTER TABLE Leverancier DROP FOREIGN KEY FK_Leverancier_Contact, DROP COLUMN ContactId');
        Schema::dropIfExists('Contact');
    }
};
