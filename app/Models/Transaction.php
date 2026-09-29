<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['cotisation_id', 'type', 'montant', 'reference_externe'])]

class Transaction extends Model
{
    public $timestamps = false;

    public function cotisation()
    {
        return $this->belongsTo(Cotisation::class, 'cotisation_id');
    }
}
