<?php

use App\Http\Requests\UpdateLeverancierRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;

function valideerLeveranciergegevens(array $wijzigingen): Validator
{
    $request = new UpdateLeverancierRequest;
    $validator = new Factory(new Translator(new ArrayLoader, 'nl'));

    return $validator->make(array_replace([
        'Naam' => 'De Bron', 'ContactPersoon' => 'Remco Veenstra', 'LeverancierNummer' => 'L1023857736',
        'Mobiel' => '06-39398825', 'Straat' => 'Leon van Bonstraat', 'Huisnummer' => 213,
        'Postcode' => '145XC', 'Stad' => 'Lunteren',
    ], $wijzigingen), $request->rules());
}

test('de nummers en de postcode uit de opdracht zijn geldig', function () {
    expect(valideerLeveranciergegevens([])->passes())->toBeTrue();
});

test('ongeldige veldwaarden worden afgewezen', function () {
    $validator = valideerLeveranciergegevens([
        'Naam' => '', 'Mobiel' => 'telefoon', 'Huisnummer' => 0, 'Postcode' => str_repeat('X', 11),
    ]);

    expect(array_keys($validator->errors()->messages()))->toBe(['Naam', 'Mobiel', 'Huisnummer', 'Postcode']);
});
