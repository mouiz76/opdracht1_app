<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPerAllergeen extends Model
{
    /**
     * De tabel die bij dit model hoort.
     */
    protected $table = 'ProductPerAllergeen';

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
        'AllergeenId',
        'IsActief',
        'Opmerking',
        'DatumAangemaakt',
        'DatumGewijzigd',
    ];

    /**
     * Relatie: Een koppeling hoort bij één product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductId', 'Id');
    }

    /**
     * Relatie: Een koppeling hoort bij één allergeen.
     */
    public function allergeen()
    {
        return $this->belongsTo(Allergeen::class, 'AllergeenId', 'Id');
    }
}
