<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * De tabel die bij dit model hoort.
     */
    protected $table = 'Product';

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
        'Barcode',
        'IsActief',
        'Opmerking',
        'DatumAangemaakt',
        'DatumGewijzigd',
    ];

    /**
     * Relatie: Een product heeft één magazijn-record.
     */
    public function magazijn()
    {
        return $this->hasOne(Magazijn::class, 'ProductId', 'Id');
    }

    /**
     * Relatie: Een product heeft meerdere leverancier-koppelingen.
     */
    public function productPerLeveranciers()
    {
        return $this->hasMany(ProductPerLeverancier::class, 'ProductId', 'Id');
    }

    /**
     * Relatie: Een product heeft meerdere allergeen-koppelingen.
     */
    public function productPerAllergenen()
    {
        return $this->hasMany(ProductPerAllergeen::class, 'ProductId', 'Id');
    }
}
