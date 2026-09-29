<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser; 
use Filament\Panel; 
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\HasAvatar;

#[Fillable(['nom', 'prenom', 'telephone', 'email', 'password', 'photo_url'])]

#[Hidden(['password'])]

class Admin extends Authenticatable implements FilamentUser, HasName, HasAvatar
{
    use Notifiable;
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

    public function canAccessPanel(Panel $panel): bool
    {
        // Pour l'instant, on autorise tout admin connecté.
        // Plus tard, tu pourras ajouter une condition : return $this->is_active === true;
        return true; 
    }

     public function getFilamentName(): string
    {
      return trim($this->prenom . ' ' . $this->nom) ?: 'Administrateur';
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->photo_url ? asset('storage/' . $this->photo_url) : null;
    }
}


    
