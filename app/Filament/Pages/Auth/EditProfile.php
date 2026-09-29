<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('photo_url')
                    ->label('Photo de profil')
                    ->image()
                    ->avatar()
                    ->disk('public')
                    ->directory('admins/photos'),
                TextInput::make('nom')->label('Nom')->required()->maxLength(255),
                TextInput::make('prenom')->label('Prénom')->required()->maxLength(255),
                TextInput::make('telephone')->label('Téléphone')->tel()->required(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}