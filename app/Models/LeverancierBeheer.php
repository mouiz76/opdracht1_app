<?php

namespace App\Models;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LeverancierBeheer
{
    public const PER_PAGINA = 4;

    public function pagina(int $pagina): LengthAwarePaginator
    {
        $pagina = min(max($pagina, 1), 1073741824);
        [$leveranciers, $totalen] = DB::selectResultSets('CALL sp_GetLeveranciersPagina(?)', [
            ($pagina - 1) * self::PER_PAGINA,
        ]);

        return new LengthAwarePaginator($leveranciers, (int) $totalen[0]->Totaal, self::PER_PAGINA, $pagina, [
            'path' => route('leverancier.beheer.index'),
        ]);
    }

    public function details(int $id): ?object
    {
        return DB::selectOne('CALL sp_GetLeverancierDetails(?)', [$id]);
    }

    /**
     * @param  array{Naam: string, ContactPersoon: string, LeverancierNummer: string, Mobiel: string, Straat: string, Huisnummer: int|string, Postcode: string, Stad: string}  $gegevens
     */
    public function wijzig(int $id, array $gegevens, bool $simuleerStoring): void
    {
        DB::statement('CALL sp_WijzigLeverancier(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $id, $gegevens['Naam'], $gegevens['ContactPersoon'], $gegevens['LeverancierNummer'],
            $gegevens['Mobiel'], $gegevens['Straat'], $gegevens['Huisnummer'], $gegevens['Postcode'],
            $gegevens['Stad'], (int) $simuleerStoring,
        ]);
    }
}
