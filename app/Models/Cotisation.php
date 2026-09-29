<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['cycle_id', 'membre_id', 'montant_attendu', 'statut_paiement', 'date_marquage', 'marque_par_id', 'notes'])]

class Cotisation extends Model
{
    public function cycle()
    {
        return $this->belongsTo(Cycle::class, 'cycle_id');
    }

    public function membre()
    {
        return $this->belongsTo(Membre::class, 'membre_id');
    }

    public function marquePar()
    {
        return $this->belongsTo(User::class, 'marque_par_id');
    }
}
