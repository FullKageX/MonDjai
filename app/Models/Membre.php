<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['tontine_id', 'user_id', 'ordre_tour', 'statut', 'date_adhesion'])]

class Membre extends Model
{
    public $timestamps = false;
    
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tontine()
    {
        return $this->belongsTo(Tontine::class, 'tontine_id');
    }

    public function cotisations()
    {
        return $this->hasMany(Cotisation::class, 'membre_id');
    }

    public function cycles()
    {
        return $this->hasMany(Cycle::class, 'beneficiaire_id');
    }
}
