<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Category;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),

                //Category selection dropdown
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->relationship('clinic.category', 'name')
                    ->required(),
                    
                //Clinic selection dropdown
                Forms\Components\Select::make('clinic_id')
                    ->label('Clinic')
                    ->relationship('clinic', 'name')
                    ->required(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('clinic.category.name')->label('Category')->sortable(),
                Tables\Columns\TextColumn::make('clinic.name')->label('Clinic')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasRole('admin')),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }

    //Role-Based Access Control
    public static function canViewAny(): bool
    {
        return self::hasRole(['admin', 'clinic', 'doctor']);
    }

    public static function canCreate(): bool
    {
        return self::hasRole(['admin', 'clinic']);
    }

    public static function canEdit($record): bool
    {
        return self::hasRole(['admin', 'clinic']);
    }

    public static function canDelete($record): bool
    {
        return self::hasRole(['admin', 'clinic']);
    }

    /**
     * Helper function to check if the authenticated user has one of the given roles.
     */
    protected static function hasRole(array $roles): bool
    {
        $user = auth()->user();
        return $user && $user->hasAnyRole($roles);
    }

    /**
     * Helper function to check if the authenticated user owns the given record.
     */
    protected static function ownsRecord($record): bool
    {
        $user = auth()->user();
        return $user && $user->id === $record->user_id;
    }


    
}
