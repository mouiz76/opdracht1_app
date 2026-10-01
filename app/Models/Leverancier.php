<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leverancier extends Model
{
    /**
     * De tabel die bij dit model hoort.
     */
    protected $table = 'Leverancier';

    /**
     * De primary key van de tabel.
     */
    protected $primaryKey = 'Id';

    /**
     * Timestamps worden niet automatisch beheerd door Laravel.
     */
    public $timestamps = false;

    /**
     * De velden die mass-assignable zijn.
     */
    protected $fillable = [
        'Naam',
        'ContactPersoon',
        'LeverancierNummer',
        'Mobiel',
        'IsActief',
        'Opmerking',
        'DatumAangemaakt',
        'DatumGewijzigd',
    ];

    /**
     * Relatie: Een leverancier heeft meerdere product-koppelingen.
     */
    public function productPerLeveranciers()
    {
        return $this->hasMany(ProductPerLeverancier::class, 'LeverancierId', 'Id');
    }
}
