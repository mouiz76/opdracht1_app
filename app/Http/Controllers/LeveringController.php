<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeveringController extends Controller
{
    /**
     * USERSTORY 2 - Scenario 1 & 2
     * Toon het formulier voor een nieuwe levering van een product.
     *
     * @param int $leverancierId  ID van de leverancier
     * @param int $productId      ID van het product
     */
    public function formulier($leverancierId, $productId)
    {
        // Roep de stored procedure aan voor productgegevens op het formulier
        $gegevens = DB::select('CALL sp_GetLeveringProduct(?, ?)', [$productId, $leverancierId]);

        // Als het product of de leverancier niet bestaat, geef 404
        if (empty($gegevens)) {
            abort(404, 'Product of leverancier niet gevonden');
        }

        $gegevens = $gegevens[0];

        return view('levering.formulier', compact('gegevens'));
    }

    /**
     * USERSTORY 2 - Scenario 1 & 2
     * Verwerk de opgeslagen levering.
     * - Scenario 1: Product actief → levering toevoegen + voorraad verhogen
     * - Scenario 2: Product inactief → foutmelding tonen, na 4 seconden redirect
     *
     * @param Request $request    De formulierdata
     * @param int $leverancierId  ID van de leverancier
     * @param int $productId      ID van het product
     */
    public function opslaan(Request $request, $leverancierId, $productId)
    {
        // Valideer de invoer (server-side)
        $request->validate([
            'aantal'                    => 'required|integer|min:1|max:9999',
            'datum_eerstvolgende_levering' => 'required|date',
        ], [
            'aantal.required'           => 'Vul het aantal producteenheden in.',
            'aantal.integer'            => 'Het aantal moet een heel getal zijn.',
            'aantal.min'                => 'Het aantal moet minimaal 1 zijn.',
            'datum_eerstvolgende_levering.required' => 'Vul de datum van de eerstvolgende levering in.',
            'datum_eerstvolgende_levering.date'     => 'De datum is niet geldig.',
        ]);

        $aantal                     = (int) $request->input('aantal');
        $datumEerstVolgendeLevering = $request->input('datum_eerstvolgende_levering');

        // Roep de stored procedure aan voor het opslaan van de levering
        // De procedure controleert zelf of het product actief is
        DB::statement('CALL sp_VoegProductLeveringToe(?, ?, ?, ?, @resultCode, @resultMessage)', [
            $leverancierId,
            $productId,
            $aantal,
            $datumEerstVolgendeLevering,
        ]);

        // Haal de uitvoerparameters op uit de stored procedure
        $result = DB::select('SELECT @resultCode AS code, @resultMessage AS message')[0];

        if ($result->code == 1) {
            // Scenario 2: Product is inactief (bijv. Winegums)
            // Haal productnaam en leveranciersnaam op voor de melding
            $productInfo    = DB::select('SELECT Naam FROM Product WHERE Id = ?', [$productId]);
            $leverancierInfo = DB::select('SELECT Naam FROM Leverancier WHERE Id = ?', [$leverancierId]);

            $productNaam    = $productInfo[0]->Naam    ?? 'Onbekend product';
            $leverancierNaam = $leverancierInfo[0]->Naam ?? 'Onbekend bedrijf';

            // Toon de foutpagina met exacte melding + redirect na 4 seconden
            return view('levering.inactief-product', compact(
                'productNaam',
                'leverancierNaam',
                'leverancierId'
            ));

        } elseif ($result->code == 0) {
            // Scenario 1: Levering succesvol toegevoegd
            // Stuur terug naar de geleverde producten van deze leverancier
            return redirect()->route('leverancier.geleverde-producten', $leverancierId)
                ->with('success', 'Levering succesvol toegevoegd aan de voorraad!');

        } else {
            // Andere fout (database probleem of leverancier levert product niet)
            return redirect()->back()
                ->with('error', 'Er is een fout opgetreden: ' . $result->message);
        }
    }
}
