<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['tontine_id', 'numero_cycle', 'beneficiaire_id', 'date_debut', 'date_echeance', 'statut'])]

class Cycle extends Model
{
    public function tontine()
    {
        return $this->belongsTo(Tontine::class, 'tontine_id');
    }

    public function beneficiaire()
    {
        return $this->belongsTo(Membre::class, 'beneficiaire_id');
    }

    public function cotisations()
    {
        return $this->hasMany(Cotisation::class, 'cycle_id');
    }

    
}
