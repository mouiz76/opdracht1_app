<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Magazijn extends Model
{
    /**
     * De tabel die bij dit model hoort.
     */
    protected $table = 'Magazijn';

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
        'ProductId',
        'VerpakkingsEenheidInKg',
        'AantalAanwezig',
        'IsActief',
        'Opmerking',
        'DatumAangemaakt',
        'DatumGewijzigd',
    ];

    /**
     * Relatie: Een magazijn-record hoort bij één product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductId', 'Id');
    }
}
