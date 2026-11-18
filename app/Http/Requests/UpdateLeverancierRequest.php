<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeverancierRequest extends FormRequest
{
    public const VELDEN = [
        'Naam' => 'Naam',
        'ContactPersoon' => 'Contactpersoon',
        'LeverancierNummer' => 'Leveranciernummer',
        'Mobiel' => 'Mobiel',
        'Straat' => 'Straatnaam',
        'Huisnummer' => 'Huisnummer',
        'Postcode' => 'Postcode',
        'Stad' => 'Stad',
    ];

    public function rules(): array
    {
        return [
            'Naam' => ['required', 'string', 'max:50'],
            'ContactPersoon' => ['required', 'string', 'max:50'],
            'LeverancierNummer' => ['required', 'string', 'max:15'],
            'Mobiel' => ['required', 'string', 'max:12', 'regex:/^\+?[0-9][0-9 -]*[0-9]$/D'],
            'Straat' => ['required', 'string', 'max:50'],
            'Huisnummer' => ['required', 'integer', 'min:1', 'max:4294967295'],
            'Postcode' => ['required', 'string', 'max:10'],
            'Stad' => ['required', 'string', 'max:50'],
        ];
    }

    public function attributes(): array
    {
        return self::VELDEN;
    }

    public function messages(): array
    {
        return [
            'required' => 'Vul :attribute in.',
            'string' => ':attribute moet tekst zijn.',
            'integer' => ':attribute moet een geheel getal zijn.',
            'string.max' => ':attribute mag maximaal :max tekens bevatten.',
            'numeric.min' => ':attribute moet minstens :min zijn.',
            'numeric.max' => ':attribute mag maximaal :max zijn.',
            'Mobiel.regex' => 'Vul een geldig mobiel nummer in.',
        ];
    }
}
