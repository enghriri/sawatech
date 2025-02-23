<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorResource\Pages;
use App\Filament\Pages\DoctorProfile;
use App\Models\Doctor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
    
                Forms\Components\Select::make('clinic_id')
                    ->label('Clinic')
                    ->relationship('clinic', 'name')
                    ->required(),
    
                //Working Hours as JSON field
                 Forms\Components\Repeater::make('working_hours')
                    ->label('Working Hours')
                    ->schema([
                        Forms\Components\Select::make('day')
                            ->label('Day')
                            ->options([
                                'monday' => 'Monday',
                                'tuesday' => 'Tuesday',
                                'wednesday' => 'Wednesday',
                                'thursday' => 'Thursday',
                                'friday' => 'Friday',
                                'saturday' => 'Saturday',
                                'sunday' => 'Sunday',
                            ])
                            ->required(),

                        Forms\Components\Repeater::make('slots')
                            ->label('Time Slots')
                            ->schema([
                                Forms\Components\TextInput::make('time')
                                    ->label('Time')
                                    ->placeholder('e.g., 9:00 AM - 12:00 PM')
                                    ->required(),
                            ])
                            ->collapsible()
                            ->itemLabel(fn (array $state) => $state['time'] ?? 'New Slot'),
                    ])
                    ->collapsible()
                    ->itemLabel(fn (array $state) => ucfirst($state['day'] ?? 'New Day'))
                    ->default([]) // Set default empty array to avoid null issue
                    ->afterStateHydrated(fn ($state, $set) => 
                        $set('working_hours', is_string($state) ? json_decode($state, true) : $state)
                    )
                    ->dehydrateStateUsing(fn ($state) => json_encode($state)),

                
                //Image upload for logo    
                Forms\Components\FileUpload::make('logo')->image(),

            ]);

    }
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
    
                // Display Working Hours in Table
                Tables\Columns\TextColumn::make('working_hours')
                    ->label('Working Hours')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';
                    
                        // Decode if the state is a JSON string
                        if (is_string($state)) {
                            $state = json_decode($state, true);
                        }
                    
                        // Ensure decoding was successful
                        if (!is_array($state)) return '-';
                    
                        $formatted = [];
                    
                        // Check if this is a single day object (not an array of days)
                        if (isset($state['day']) && isset($state['slots'])) {
                            $state = [$state]; // Wrap it in an array for consistent processing
                        }
                    
                        foreach ($state as $dayData) {
                            $day = ucfirst($dayData['day'] ?? 'Unknown');
                    
                            // Ensure 'slots' is an array
                            $slots = $dayData['slots'] ?? [];
                            if (!is_array($slots)) {
                                $slots = json_decode($slots, true) ?? [];
                            }
                    
                            // Extract and format time slots
                            $slotTimes = array_map(fn($slot) => $slot['time'] ?? '', $slots);
                    
                            // Concatenate day and slots
                            if (!empty($slotTimes)) {
                                $formatted[] = "$day: " . implode(', ', $slotTimes);
                            }
                        }
                    
                        return !empty($formatted) ? implode(' | ', $formatted) : '-';
                    })
                    
                    
                    ->limit(50),

                
                // Logo
                Tables\Columns\ImageColumn::make('profile_picture')
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->hasRole('admin') || auth()->user()->hasRole('clinic')),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDoctors::route('/'),
            'create' => Pages\CreateDoctor::route('/create'),
            'edit' => Pages\EditDoctor::route('/{record}/edit'),
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
        return self::hasRole(['admin']) || (self::hasRole(['clinic']) || self::ownsRecord($record));
    }
    
    public static function canDelete($record): bool
    {
        return self::hasRole(['admin']) || (self::hasRole(['clinic']) || self::ownsRecord($record));
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
