<?php

namespace App\Http\Controllers;

use App\Models\Magazijn;
use App\Models\Product;
use App\Models\ProductPerLeverancier;
use App\Models\ProductPerAllergeen;
use Illuminate\Http\Request;

class MagazijnController extends Controller
{
    /**
     * Toon het overzicht van alle producten in het magazijn.
     * Producten zijn gesorteerd op Barcode oplopend.
     */
    public function index()
    {
        $magazijnItems = Magazijn::with('product')
            ->join('Product', 'Magazijn.ProductId', '=', 'Product.Id')
            ->orderBy('Product.Barcode', 'asc')
            ->select('Magazijn.*')
            ->get();

        return view('magazijn.overzicht', compact('magazijnItems'));
    }

    /**
     * Toon de leveringsinformatie van een product.
     * User Story 01 - Scenario 01 & 02
     */
    public function leveringInfo($productId)
    {
        $product = Product::findOrFail($productId);

        // Haal het magazijn-record op voor dit product
        $magazijn = Magazijn::where('ProductId', $productId)->first();

        // Haal alle leveringen op voor dit product, gesorteerd op DatumLevering oplopend
        $leveringen = ProductPerLeverancier::with('leverancier')
            ->where('ProductId', $productId)
            ->orderBy('DatumLevering', 'asc')
            ->get();

        // Haal de leverancier informatie op (eerste leverancier die dit product levert)
        $leverancierInfo = null;
        if ($leveringen->isNotEmpty()) {
            $leverancierInfo = $leveringen->first()->leverancier;
        }

        // Scenario 02: Controleer of het product op voorraad is (AantalAanwezig is NULL)
        $geenVoorraad = ($magazijn && $magazijn->AantalAanwezig === null);

        // Haal de verwachte eerstvolgende levering op voor het bericht
        $verwachteLevering = null;
        if ($geenVoorraad && $leveringen->isNotEmpty()) {
            $laatsteLevering = $leveringen->last();
            $verwachteLevering = $laatsteLevering->DatumEerstVolgendeLevering;
        }

        return view('magazijn.levering-info', compact(
            'product',
            'leveringen',
            'leverancierInfo',
            'geenVoorraad',
            'verwachteLevering'
        ));
    }

    /**
     * Toon de allergeneninformatie van een product.
     * User Story 02 - Scenario 01 & 02
     */
    public function allergenenInfo($productId)
    {
        $product = Product::findOrFail($productId);

        // Haal alle allergenen op voor dit product, gesorteerd op Naam oplopend
        $allergenen = ProductPerAllergeen::with('allergeen')
            ->where('ProductId', $productId)
            ->join('Allergeen', 'ProductPerAllergeen.AllergeenId', '=', 'Allergeen.Id')
            ->orderBy('Allergeen.Naam', 'asc')
            ->select('ProductPerAllergeen.*')
            ->get();

        // Scenario 02: Controleer of er geen allergenen zijn
        $geenAllergenen = $allergenen->isEmpty();

        return view('magazijn.allergenen-info', compact(
            'product',
            'allergenen',
            'geenAllergenen'
        ));
    }
}
