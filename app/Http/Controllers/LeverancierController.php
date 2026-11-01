<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeverancierController extends Controller
{
    /**
     * USERSTORY 1 - Scenario 1 & 2
     * Toon het overzicht van alle leveranciers met het aantal geleverde producten.
     * Gesorteerd op aantal producten (aflopend).
     * Roept de Stored Procedure sp_GetLeveranciersOverzicht aan.
     */
    public function overzicht()
    {
        // Roep de stored procedure aan via PDO (Laravel DB::select)
        $leveranciers = DB::select('CALL sp_GetLeveranciersOverzicht()');

        return view('leverancier.overzicht', compact('leveranciers'));
    }

    /**
     * USERSTORY 1 - Scenario 1 & 2
     * Toon de geleverde producten van één specifieke leverancier.
     * Scenario 2: Als er geen producten zijn, toon een melding + redirect na 3 seconden.
     *
     * @param int $leverancierId  Het ID van de leverancier
     */
    public function geleverdeProducten($leverancierId)
    {
        // Haal de leverancier op (veilig met parameterbinding)
        $leverancier = DB::select(
            'SELECT Id, Naam, ContactPersoon, LeverancierNummer, Mobiel FROM Leverancier WHERE Id = ?',
            [$leverancierId]
        );

        // Controleer of de leverancier bestaat
        if (empty($leverancier)) {
            abort(404, 'Leverancier niet gevonden');
        }

        $leverancier = $leverancier[0];

        // Roep de stored procedure aan: geleverde producten van deze leverancier
        $producten = DB::select('CALL sp_GetGeleverdeProducten(?)', [$leverancierId]);

        // Geen producten = Scenario 2 (bijv. Quality Street)
        $geenProducten = empty($producten);

        return view('leverancier.geleverde-producten', compact(
            'leverancier',
            'producten',
            'geenProducten'
        ));
    }
}
