<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['nom', 'prenom', 'email', 'password', 'telephone', 'photo_url', 'sexe', 'date_naissance', 'ville'])]
#[Hidden(['password'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function tontinesCreees()
    {
        return $this->hasMany(Tontine::class, 'createur_id');
    }

    public function membreDe()
    {
        return $this->hasMany(Membre::class, 'user_id');
    }

    public function cotisations()
    {
        return $this->hasManyThrough(Cotisation::class, Membre::class, 'user_id', 'membre_id');
    }
}
