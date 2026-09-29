<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Administration';
    protected static ?string $navigationLabel = 'Utilisateurs';
    protected static ?string $modelLabel = 'utilisateur';
    protected static ?string $pluralModelLabel = 'utilisateurs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('prenom')->label('Prénom')->required()->maxLength(255),
                TextInput::make('nom')->label('Nom')->required()->maxLength(255),
                TextInput::make('telephone')->label('Téléphone')->tel()->required()->unique(ignoreRecord: true),
                TextInput::make('email')->label('Adresse e-mail')->email()->required()->unique(ignoreRecord: true),
                Select::make('sexe')
                    ->label('Sexe')
                    ->options(['Homme' => 'Homme', 'Femme' => 'Femme'])
                    ->required(),
                DatePicker::make('date_naissance')
                    ->label('Date de naissance'),
                TextInput::make('ville')
                    ->label('Ville')
                    ->maxLength(255),
                TextInput::make('password')->label('Mot de passe')->password()->required(fn (string $operation): bool => $operation === 'create')->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? $state : null)->dehydrated(fn (?string $state): bool => filled($state)),
                FileUpload::make('photo_url')
                ->label('Photo de profil')
                ->image()
                ->avatar()
                ->directory('users/photos'),        
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo_url')
                    ->label('Photo')
                    ->circular(),
                TextColumn::make('prenom')->label('Prénom')->searchable()->sortable(),
                TextColumn::make('nom')->label('Nom')->searchable()->sortable(),
                TextColumn::make('telephone')->label('Téléphone')->searchable(),
                TextColumn::make('email')->label('E-mail')->searchable(),
                TextColumn::make('created_at')->label('Inscrit le')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Supprimer'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
