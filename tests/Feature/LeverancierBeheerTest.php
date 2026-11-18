<?php

use App\Http\Requests\UpdateLeverancierRequest;
use App\Models\LeverancierBeheer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

afterEach(function () {
    DB::setDefaultConnection('sqlite');
});

function gebruikLeveranciersTestdatabase(): void
{
    $database = getenv('JAMIN_MYSQL_TEST_DATABASE');
    if (! is_string($database) || ! preg_match('/^jamin_[a-z0-9_]+_test$/D', $database)) {
        test()->markTestSkipped('Stel JAMIN_MYSQL_TEST_DATABASE in op een gemigreerde MySQL-testdatabase.');
    }

    config(['database.connections.mysql.database' => $database]);
    DB::setDefaultConnection('mysql');
}

function leveranciersFormulier(int $id): array
{
    return array_intersect_key((array) app(LeverancierBeheer::class)->details($id), UpdateLeverancierRequest::VELDEN);
}

test('beheer is afgeschermd en manager ziet de link op het dashboard', function () {
    $this->get(route('leverancier.beheer.index'))->assertRedirect(route('login'));
    $this->actingAs(User::factory()->make(['role' => 'magazijnmedewerker']))
        ->get(route('leverancier.beheer.index'))->assertForbidden();
    $this->actingAs(User::factory()->make(['role' => 'manager']))
        ->get(route('dashboard'))->assertOk()->assertSee('Wijzigen Leveranciers');
});

test('mysql levert vier leveranciers op de eerste pagina en twee op de tweede', function () {
    gebruikLeveranciersTestdatabase();
    $this->actingAs(User::factory()->make(['role' => 'manager']));

    $this->get(route('leverancier.beheer.index'))->assertOk()
        ->assertViewHas('leveranciers', fn ($pagina) => $pagina->count() === 4 && $pagina->total() === 6)
        ->assertSee('Volgende')->assertSee('Astra Sweets')->assertDontSee('De Bron');
    $this->get(route('leverancier.beheer.index', ['page' => 2]))->assertOk()
        ->assertViewHas('leveranciers', fn ($pagina) => $pagina->count() === 2)
        ->assertSee('Vorige')->assertSee('De Bron')->assertDontSee('Astra Sweets');
});

test('astra sweets slaat mobiel en straat op en toont de succesmelding', function () {
    gebruikLeveranciersTestdatabase();
    $this->actingAs(User::factory()->make(['role' => 'manager']));

    $voor = leveranciersFormulier(2);
    $this->get(route('leverancier.beheer.edit', 2))->assertOk()->assertSee($voor['Straat']);

    try {
        $this->followingRedirects()->patch(route('leverancier.beheer.update', 2), array_replace($voor, [
            'Mobiel' => '06-39398825', 'Straat' => 'Den Dolderlaan',
        ]))->assertOk()->assertSee('De wijzigingen zijn doorgevoerd')->assertSee('3000');

        $this->assertDatabaseHas('Leverancier', ['Id' => 2, 'Mobiel' => '06-39398825'], 'mysql');
        $this->assertDatabaseHas('Contact', ['Id' => 2, 'Straat' => 'Den Dolderlaan'], 'mysql');
        $this->get(route('leverancier.beheer.show', 2))->assertOk()
            ->assertSee('Astra Sweets')->assertSee('06-39398825')->assertSee('Den Dolderlaan');
    } finally {
        app(LeverancierBeheer::class)->wijzig(2, $voor, false);
    }
});

test('de bron toont de foutmelding en mysql draait beide tabelwijzigingen terug', function () {
    gebruikLeveranciersTestdatabase();
    config(['app.simuleer_leveranciersstoring' => true]);
    $this->actingAs(User::factory()->make(['role' => 'manager']));

    $voor = leveranciersFormulier(5);
    $this->followingRedirects()->patch(route('leverancier.beheer.update', ['leverancierId' => 5, 'page' => 2]), array_replace($voor, [
        'Mobiel' => '06-39398825', 'Straat' => 'Ongeldige opslagproef',
    ]))->assertOk()
        ->assertSee('Door een technische storing is het niet mogelijk de wijziging door te voeren. Probeer het op een later moment nog eens')
        ->assertSee('3000')->assertSee(route('leverancier.beheer.show', ['leverancierId' => 5, 'page' => 2]));

    expect(leveranciersFormulier(5))->toBe($voor);
    $this->get(route('leverancier.beheer.show', 5))->assertOk()->assertSee($voor['Mobiel']);
});

test('ongeldige invoer wordt geweigerd voordat mysql gegevens opslaat', function () {
    gebruikLeveranciersTestdatabase();
    $this->actingAs(User::factory()->make(['role' => 'manager']));

    $voor = leveranciersFormulier(2);
    $this->from(route('leverancier.beheer.edit', 2))
        ->patch(route('leverancier.beheer.update', 2), array_replace($voor, ['Mobiel' => 'ongeldig', 'Huisnummer' => -1]))
        ->assertRedirect(route('leverancier.beheer.edit', 2))->assertSessionHasErrors(['Mobiel', 'Huisnummer']);

    expect(leveranciersFormulier(2))->toBe($voor);
});

test('bestaande leveranciersroutes en levering gebruiken hun eerdere procedures', function () {
    gebruikLeveranciersTestdatabase();
    $this->actingAs(User::factory()->make());

    $this->get(route('leverancier.overzicht'))->assertOk()->assertSee('Quality Street');
    $this->get(route('leverancier.geleverde-producten', 1))->assertOk()->assertSee('Mintnopjes');
    $this->get(route('levering.formulier', [4, 10]))->assertOk()->assertSee('Winegums');
});
