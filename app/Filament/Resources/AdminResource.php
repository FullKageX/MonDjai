<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;
use App\Models\Admin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;



class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Administration';
    protected static ?string $navigationLabel = 'Admins';
    protected static ?string $modelLabel = 'administrateur';
    protected static ?string $pluralModelLabel = 'administrateurs';


    public static function canViewAny(): bool
    {
        return Auth::guard('admin')->user()?->role === 'SuperAdmin';
    }

    public static function canCreate(): bool
    {
        return Auth::guard('admin')->user()?->role === 'SuperAdmin';
    }

    public static function form(Form $form): Form
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
                Select::make('role')
                    ->label('Rôle')
                    ->options(['SuperAdmin' => 'SuperAdmin', 'Admin' => 'Admin'])
                    ->required(),
                TextInput::make('password')
                    ->label('Mot de passe')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? $state : null)
                    ->dehydrated(fn (?string $state): bool => filled($state)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo_url')
                    ->label('Photo')
                    ->disk('public')
                    ->circular(),
                TextColumn::make('nom')->label('Nom')->searchable()->sortable(),
                TextColumn::make('prenom')->label('Prénom')->searchable()->sortable(),
                TextColumn::make('telephone')->label('Téléphone')->searchable(),
                TextColumn::make('role')->label('Rôle')->searchable()->sortable(),
                TextColumn::make('created_at')->label('Créé le')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
