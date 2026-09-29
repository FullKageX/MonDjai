<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;


#[Fillable(['nom', 'description', 'montant_cotisation', 'frequence', 'jour_echeance', 'ordre_versement', 'date_debut', 'intervalle_jours', 'statut', 'createur_id'])]
class Tontine extends Model
{
    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
        ];
    }
    public function createur()
    {
        return $this->belongsTo(User::class, 'createur_id');
    }

    public function membres()
    {
        return $this->hasMany(Membre::class, 'tontine_id');
    
    }

    public function cycles()
    {
        return $this->hasMany(Cycle::class, 'tontine_id');
    }
}
