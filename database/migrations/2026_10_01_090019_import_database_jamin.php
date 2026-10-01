<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sql = file_get_contents(database_path('migrations/Database_jamin.sql'));

        if (DB::getDriverName() === 'sqlite') {
            $sql = preg_replace('/--.*$/m', '', $sql);
            $sql = str_replace('SET FOREIGN_KEY_CHECKS=0;', '', $sql);
            $sql = str_replace('SET FOREIGN_KEY_CHECKS=1;', '', $sql);
            $sql = str_replace('ENGINE=InnoDB;', ';', $sql);
            $sql = str_replace('ENGINE=InnoDB', '', $sql);
            $sql = str_replace('DateTime(6)', 'DATETIME', $sql);
            $sql = preg_replace('/(TINYINT|SMALLINT|INT)\s+(UNSIGNED\s+)?NOT NULL\s+AUTO_INCREMENT/i', 'INTEGER PRIMARY KEY AUTOINCREMENT', $sql);
            $sql = str_replace('TINYINT UNSIGNED', 'INTEGER', $sql);
            $sql = str_replace('SMALLINT UNSIGNED', 'INTEGER', $sql);
            $sql = str_replace('BIT', 'INTEGER', $sql);
            $sql = str_replace('SYSDATE(6)', "datetime('now')", $sql);
            $sql = preg_replace('/,CONSTRAINT\s+PK_\w+_Id\s+PRIMARY\s+KEY\s*\(Id\)/i', '', $sql);

            Schema::disableForeignKeyConstraints();
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            foreach ($statements as $stmt) {
                if (!empty($stmt)) {
                    DB::statement($stmt);
                }
            }
            Schema::enableForeignKeyConstraints();
        } else {
            DB::unprepared($sql);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('ProductPerAllergeen');
        Schema::dropIfExists('ProductPerLeverancier');
        Schema::dropIfExists('Magazijn');
        Schema::dropIfExists('Leverancier');
        Schema::dropIfExists('Product');
        Schema::dropIfExists('Allergeen');
        Schema::enableForeignKeyConstraints();
    }
};
