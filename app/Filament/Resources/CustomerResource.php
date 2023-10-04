<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Tienda';
    protected static ?string $modelLabel = 'Cliente';

    protected static ?string $pluralModelLabel = 'Mis Clientes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información del Cliente')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->autofocus()
                            ->placeholder('Nombre del Cliente')
                            ->label('Nombre del Cliente')
                            ->required()
                            ->maxValue(50),
                        Forms\Components\TextInput::make('email')
                            ->autofocus()
                            ->placeholder('Correo Electrónico')
                            ->label('Correo Electrónico')
                            ->required()
                            ->live(onBlur: true),
                        Forms\Components\TextInput::make('phone')
                            ->autofocus()
                            ->placeholder('Teléfono')
                            ->label('Teléfono')
                            ->required()
                            ->live(onBlur: true),
                        Forms\Components\Datepicker::make('date_of_birth')
                            ->autofocus()
                            ->placeholder('Fecha de Nacimiento')
                            ->label('Fecha de Nacimiento')
                            ->required(),
                        Forms\Components\TextInput::make('city')
                            ->autofocus()
                            ->placeholder('Ciudad')
                            ->label('Ciudad')
                            ->required() ,
                        forms\Components\TextInput::make('zip_code')
                            ->autofocus()
                            ->placeholder('codigo postal')
                            ->label('codigo postal')
                            ->required() ,
                        Forms\Components\TextInput::make('address')
                            ->autofocus()
                            ->placeholder('Dirección')
                            ->label('Dirección')
                            ->required()
                            ->columnSpan(2),


                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->emptyStateDescription('Una vez que creas tu primer cliente, aparecerá aquí.')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('Ciudad')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Fecha de Nacimiento')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
