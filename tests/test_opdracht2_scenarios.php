<?php

/**
 * Testscript voor Backend Opdracht 2 - Alle 4 Scenario's + Opdracht 1 Verificatie.
 * Dit script test rechtstreeks tegen de MySQL database via Laravel.
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

echo "====================================================\n";
echo "TEST SUITE: BACKEND OPDRACHT 2 JAMIN - ALLE SCENARIO'S\n";
echo "====================================================\n\n";

$passCount = 0;
$failCount = 0;

function assertTest($description, $condition) {
    global $passCount, $failCount;
    if ($condition) {
        echo "  [PASS] $description\n";
        $passCount++;
    } else {
        echo "  [FAIL] $description\n";
        $failCount++;
    }
}

// Haal testgebruiker op
$user = User::first();
if (!$user) {
    die("Geen gebruiker gevonden in database.\n");
}
Auth::login($user);

// -----------------------------------------------------------
// TEST 1: STORED PROCEDURES DIRECT
// -----------------------------------------------------------
echo "--- TEST GROEP 1: Stored Procedures Sanity & Veiligheid ---\n";

// sp_GetLeveranciersOverzicht
$leveranciers = DB::select('CALL sp_GetLeveranciersOverzicht()');
assertTest('sp_GetLeveranciersOverzicht geeft 6 leveranciers terug', count($leveranciers) === 6);
assertTest('sp_GetLeveranciersOverzicht sorteert op AantalProducten DESC (hoogste eerst)',
    $leveranciers[0]->AantalProducten >= $leveranciers[1]->AantalProducten &&
    $leveranciers[4]->Naam === 'Basset' && $leveranciers[4]->AantalProducten === 1 &&
    $leveranciers[5]->Naam === 'Quality Street' && $leveranciers[5]->AantalProducten === 0
);

// sp_GetGeleverdeProducten
$productenVenco = DB::select('CALL sp_GetGeleverdeProducten(1)');
assertTest('sp_GetGeleverdeProducten(1) voor Venco geeft 3 producten', count($productenVenco) === 3);
assertTest('sp_GetGeleverdeProducten sorteert op AantalAanwezig DESC',
    $productenVenco[0]->AantalAanwezig >= $productenVenco[1]->AantalAanwezig &&
    $productenVenco[1]->AantalAanwezig >= $productenVenco[2]->AantalAanwezig
);

$productenQS = DB::select('CALL sp_GetGeleverdeProducten(6)');
assertTest('sp_GetGeleverdeProducten(6) voor Quality Street geeft lege array', empty($productenQS));

// sp_GetLeveringProduct (Eis 2: controleer dat product daadwerkelijk door leverancier geleverd wordt)
$mismatch = DB::select('CALL sp_GetLeveringProduct(10, 1)'); // Winegums bij Venco
assertTest('sp_GetLeveringProduct(10, 1) mismatch Winegums/Venco geeft 0 rijen (Eis 2)', empty($mismatch));

$match = DB::select('CALL sp_GetLeveringProduct(10, 4)'); // Winegums bij Basset
assertTest('sp_GetLeveringProduct(10, 4) match Winegums/Basset geeft 1 rij', count($match) === 1 && $match[0]->ProductNaam === 'Winegums');

// sp_VoegProductLeveringToe veiligheid: negatief of 0 aantal
DB::statement('CALL sp_VoegProductLeveringToe(?, ?, ?, ?, @resCode, @resMsg)', [1, 1, 0, '2026-11-20']);
$resInvalid = DB::select('SELECT @resCode AS code, @resMsg AS msg')[0];
assertTest('sp_VoegProductLeveringToe met aantal=0 weigert met code 4', $resInvalid->code === 4);

echo "\n";

// -----------------------------------------------------------
// TEST 2: USER STORY 1 - SCENARIO 1 (Overzicht leverancier & geleverde producten)
// -----------------------------------------------------------
echo "--- TEST GROEP 2: US 1 - Scenario 1 (Overzicht leverancier & Geleverde producten) ---\n";

$req1 = Request::create(route('leverancier.overzicht'), 'GET');
$req1->setUserResolver(fn() => $user);
$res1 = $app->handle($req1);

assertTest('GET /leveranciers status 200', $res1->getStatusCode() === 200);
$content1 = $res1->getContent();
assertTest('Overzicht leverancier bevat tabelkopjes en titel', str_contains($content1, 'Overzicht leverancier') && str_contains($content1, 'Aantal producten'));
assertTest('Overzicht leverancier toont Quality Street met 0 producten', str_contains($content1, 'Quality Street') && preg_match('/>\s*0\s*</', $content1));
assertTest('Overzicht leverancier toont Basset met 1 product', str_contains($content1, 'Basset') && preg_match('/>\s*1\s*</', $content1));

// Geleverde producten van Venco
$req1b = Request::create(route('leverancier.geleverde-producten', 1), 'GET');
$req1b->setUserResolver(fn() => $user);
$res1b = $app->handle($req1b);

assertTest('GET /leveranciers/1/producten status 200', $res1b->getStatusCode() === 200);
$content1b = $res1b->getContent();
assertTest('Toont leveranciergegevens Venco (Bert van Linge)', str_contains($content1b, 'Bert van Linge'));
assertTest('Toont geleverde producten van Venco (Mintnopjes, Schoolkrijt, Honingdrop)',
    str_contains($content1b, 'Mintnopjes') &&
    str_contains($content1b, 'Schoolkrijt') &&
    str_contains($content1b, 'Honingdrop')
);
assertTest('Bevat link naar nieuwe levering formulier (+ icoon)', str_contains($content1b, route('levering.formulier', [1, 1])));

echo "\n";

// -----------------------------------------------------------
// TEST 3: USER STORY 1 - SCENARIO 2 (Quality Street zonder geleverde producten)
// -----------------------------------------------------------
echo "--- TEST GROEP 3: US 1 - Scenario 2 (Leverancier zonder producten - Quality Street) ---\n";

$req2 = Request::create(route('leverancier.geleverde-producten', 6), 'GET');
$req2->setUserResolver(fn() => $user);
$res2 = $app->handle($req2);

assertTest('GET /leveranciers/6/producten status 200', $res2->getStatusCode() === 200);
$content2 = $res2->getContent();
assertTest('Toont exacte melding: "Dit bedrijf heeft tot nu toe geen producten geleverd aan Jamin"',
    str_contains($content2, 'Dit bedrijf heeft tot nu toe geen producten geleverd aan Jamin')
);
assertTest('Bevat automatische redirect na 3 seconden naar Overzicht leverancier',
    str_contains($content2, route('leverancier.overzicht')) &&
    (str_contains($content2, '3000') || str_contains($content2, 'content="3;url='))
);

echo "\n";

// -----------------------------------------------------------
// TEST 4: USER STORY 2 - SCENARIO 1 (Levering toevoegen voor actief product)
// -----------------------------------------------------------
echo "--- TEST GROEP 4: US 2 - Scenario 1 (Levering toevoegen actief product - Mintnopjes) ---\n";

// Formulier openen
$req4a = Request::create(route('levering.formulier', [1, 1]), 'GET');
$req4a->setUserResolver(fn() => $user);
$res4a = $app->handle($req4a);

assertTest('GET /leveranciers/1/producten/1/levering status 200 (Formulier opent)', $res4a->getStatusCode() === 200);
$content4a = $res4a->getContent();
assertTest('Formulier toont Mintnopjes en leverancier Venco',
    str_contains($content4a, 'Mintnopjes') &&
    str_contains($content4a, '8719587231278') &&
    str_contains($content4a, 'Venco')
);
assertTest('Formulier bevat inputvelden aantal, datum en knop Sla op',
    str_contains($content4a, 'name="aantal"') &&
    str_contains($content4a, 'name="datum_eerstvolgende_levering"') &&
    str_contains($content4a, 'Sla op')
);

// Opslaan van een levering
$voorraadVoor = DB::select('SELECT AantalAanwezig FROM Magazijn WHERE ProductId = 1')[0]->AantalAanwezig;
$aantalLeveringenVoor = DB::select('SELECT COUNT(*) as c FROM ProductPerLeverancier WHERE ProductId = 1')[0]->c;

$postData = [
    'aantal' => 10,
    'datum_eerstvolgende_levering' => '2026-11-20',
    '_token' => csrf_token(),
];
$req4b = Request::create(route('levering.opslaan', [1, 1]), 'POST', $postData);
$req4b->setUserResolver(fn() => $user);
$res4b = $app->handle($req4b);

assertTest('POST /leveranciers/1/producten/1/levering redirect (302)', $res4b->getStatusCode() === 302);
assertTest('Redirect verwijst naar Geleverde producten van Venco',
    $res4b->headers->get('Location') === route('leverancier.geleverde-producten', 1)
);

$voorraadNa = DB::select('SELECT AantalAanwezig FROM Magazijn WHERE ProductId = 1')[0]->AantalAanwezig;
$aantalLeveringenNa = DB::select('SELECT COUNT(*) as c FROM ProductPerLeverancier WHERE ProductId = 1')[0]->c;

assertTest('Voorraad in Magazijn is verhoogd met 10 (van ' . $voorraadVoor . ' naar ' . $voorraadNa . ')',
    (int)$voorraadNa === (int)$voorraadVoor + 10
);
assertTest('Nieuwe levering is toegevoegd aan ProductPerLeverancier',
    (int)$aantalLeveringenNa === (int)$aantalLeveringenVoor + 1
);

// Schoon testlevering op zodat de database intact blijft
$laatsteLevering = DB::table('ProductPerLeverancier')->where('ProductId', 1)->orderBy('Id', 'desc')->first();
if ($laatsteLevering && (int)$laatsteLevering->Aantal === 10 && $laatsteLevering->DatumEerstVolgendeLevering === '2026-11-20') {
    DB::table('ProductPerLeverancier')->where('Id', $laatsteLevering->Id)->delete();
    DB::table('Magazijn')->where('ProductId', 1)->decrement('AantalAanwezig', 10);
}

echo "\n";

// -----------------------------------------------------------
// TEST 5: USER STORY 2 - SCENARIO 2 (Inactief product Winegums - Eis 1)
// -----------------------------------------------------------
echo "--- TEST GROEP 5: US 2 - Scenario 2 (Inactief product Winegums - Eis 1) ---\n";

// 1. Formulier moet GEWOON OPENEN (niet geblokkeerd op GET)
$req5a = Request::create(route('levering.formulier', [4, 10]), 'GET');
$req5a->setUserResolver(fn() => $user);
$res5a = $app->handle($req5a);

assertTest('GET /leveranciers/4/producten/10/levering status 200 (Eis 1: formulier opent gewoon!)', $res5a->getStatusCode() === 200);
$content5a = $res5a->getContent();
assertTest('Formulier toont Winegums, barcode 8719587327527 en Basset',
    str_contains($content5a, 'Winegums') &&
    str_contains($content5a, '8719587327527') &&
    str_contains($content5a, 'Basset')
);
assertTest('Formulier bevat invoervelden en Sla op knop',
    str_contains($content5a, 'name="aantal"') &&
    str_contains($content5a, 'name="datum_eerstvolgende_levering"') &&
    str_contains($content5a, 'Sla op')
);

// 2. Klik op Sla op (POST naar opslaan)
$voorraadWinegumsVoor = DB::select('SELECT AantalAanwezig FROM Magazijn WHERE ProductId = 10')[0]->AantalAanwezig;
$leveringenWinegumsVoor = DB::select('SELECT COUNT(*) as c FROM ProductPerLeverancier WHERE ProductId = 10')[0]->c;

$postDataWinegums = [
    'aantal' => 15,
    'datum_eerstvolgende_levering' => '2026-11-20',
    '_token' => csrf_token(),
];
$req5b = Request::create(route('levering.opslaan', [4, 10]), 'POST', $postDataWinegums);
$req5b->setUserResolver(fn() => $user);
$res5b = $app->handle($req5b);

assertTest('POST /leveranciers/4/producten/10/levering status 200 (Toont inactief pagina)', $res5b->getStatusCode() === 200);
$content5b = $res5b->getContent();

// Exacte melding uit de opdracht:
// "Het product Winegums van de leverancier Basset wordt niet meer geproduceerd"
assertTest('Toont exacte melding: "Het product Winegums van de leverancier Basset wordt niet meer geproduceerd"',
    str_contains($content5b, 'Het product Winegums van de leverancier Basset wordt niet meer geproduceerd')
);

assertTest('Stuurt na 4 seconden terug naar Geleverde producten van Basset',
    str_contains($content5b, route('leverancier.geleverde-producten', 4)) &&
    (str_contains($content5b, '4000') || str_contains($content5b, 'content="4;url='))
);

$voorraadWinegumsNa = DB::select('SELECT AantalAanwezig FROM Magazijn WHERE ProductId = 10')[0]->AantalAanwezig;
$leveringenWinegumsNa = DB::select('SELECT COUNT(*) as c FROM ProductPerLeverancier WHERE ProductId = 10')[0]->c;

assertTest('Voorraad van Winegums is NIET gewijzigd (blijft ongewijzigd)',
    $voorraadWinegumsNa === $voorraadWinegumsVoor
);
assertTest('Geen nieuwe levering toegevoegd aan ProductPerLeverancier voor Winegums',
    (int)$leveringenWinegumsNa === (int)$leveringenWinegumsVoor
);

echo "\n";

// -----------------------------------------------------------
// TEST 6: EIS 2 - Mismatch leverancier en product
// -----------------------------------------------------------
echo "--- TEST GROEP 6: Eis 2 (Mismatch product en leverancier) ---\n";

try {
    $req6 = Request::create(route('levering.formulier', [1, 10]), 'GET'); // Venco levert geen Winegums
    $req6->setUserResolver(fn() => $user);
    $res6 = $app->handle($req6);
    $status6 = $res6->getStatusCode();
} catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
    $status6 = $e->getStatusCode();
}

assertTest('GET met mismatching leverancier/product levert 404 Not Found op (Eis 2)', $status6 === 404);

echo "\n";

// -----------------------------------------------------------
// TEST 7: OPDRACHT 1 BLIJFT WERKEN
// -----------------------------------------------------------
echo "--- TEST GROEP 7: Backend Opdracht 1 Verificatie ---\n";

$reqOpdr1 = Request::create(route('magazijn.overzicht'), 'GET');
$reqOpdr1->setUserResolver(fn() => $user);
$resOpdr1 = $app->handle($reqOpdr1);
assertTest('Overzicht Magazijn Jamin (/magazijn) status 200', $resOpdr1->getStatusCode() === 200);

$reqOpdr1b = Request::create(route('magazijn.levering-info', 1), 'GET');
$reqOpdr1b->setUserResolver(fn() => $user);
$resOpdr1b = $app->handle($reqOpdr1b);
assertTest('Levering Info Mintnopjes (/magazijn/levering/1) status 200', $resOpdr1b->getStatusCode() === 200);

$reqOpdr1c = Request::create(route('magazijn.allergenen-info', 13), 'GET');
$reqOpdr1c->setUserResolver(fn() => $user);
$resOpdr1c = $app->handle($reqOpdr1c);
assertTest('Allergenen Info Zoute Ruitjes (/magazijn/allergenen/13) status 200', $resOpdr1c->getStatusCode() === 200);

echo "\n====================================================\n";
echo "RESULTAAT: $passCount TESTS GESLAAGD, $failCount MISLUKT\n";
echo "====================================================\n";

if ($failCount > 0) {
    exit(1);
}
