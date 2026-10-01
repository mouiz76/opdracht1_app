<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allergeen extends Model
{
    /**
     * De tabel die bij dit model hoort.
     */
    protected $table = 'Allergeen';

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
        'Omschrijving',
        'IsActief',
        'Opmerking',
        'DatumAangemaakt',
        'DatumGewijzigd',
    ];

    /**
     * Relatie: Een allergeen heeft meerdere product-koppelingen.
     */
    public function productPerAllergenen()
    {
        return $this->hasMany(ProductPerAllergeen::class, 'AllergeenId', 'Id');
    }
}
