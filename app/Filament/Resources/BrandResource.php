<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Brand;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use PHPUnit\Metadata\Api\Groups;
use Filament\Forms\Components\Group;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BrandResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BrandResource\RelationManagers;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;



    protected static ?string $navigationLabel = 'Marcas';

    protected static ?string $navigationGroup = 'Tienda';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Marca';

    protected static ?string $pluralModelLabel = 'Mis Marcas';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                        Forms\Components\Group::make()

                           ->schema([
                                        Forms\Components\section::make('Información de la Marca')

                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->autofocus()
                                                    ->placeholder('Nombre de la Marca')
                                                    ->label('Nombre de la Marca')
                                                    ->required()
                                                    ->live(onBlur:true)
                                                    ->unique()
                                                    ->afterStateUpdated(function (String $operation,  $state, Forms\Set $set) {
                                                        if ($operation !== 'create' ) {
                                                            return;
                                                        }
                                                        $set('slug', Str::slug($state));
                                                    }),
                                                Forms\Components\TextInput::make('slug')
                                                    ->label('slug')
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->required()
                                                    ->unique(),
                                                Forms\Components\TextInput::make('url')
                                                    ->label('Web de la Marca')
                                                    ->url()
                                                    ->placeholder('Web de la Marca')
                                                    ->required()
                                                    ->unique()
                                                ->columnSpan('full'),

                                                Forms\Components\MarkdownEditor::make('description')
                                                    ->label('Descripción')
                                                    ->placeholder('Descripción de la Marca')
                                                    ->required()
                                                    ->columnSpan('full'),
                                            ])->columns('2'),
                           ]),

                           Forms\Components\Group::make()

                           ->schema([
                                        Forms\Components\section::make('Estado de la Marca')

                                            ->schema([
                                                Forms\Components\Toggle::make('is_visible')
                                                    ->label('Visible')
                                                    ->helperText('Si la Marca no es visible, no se mostrará en la tienda')
                                                    ->required()
                                                    ->default(true),
                                            ]),



                                         Forms\Components\Group::make()

                                            ->schema([
                                                        Forms\Components\section::make('Color de la Marca')

                                                                ->schema([
                                                                  Forms\Components\ColorPicker::make('primary_hex')
                                                                        ->label('Color Primario')
                                                                        ->helperText('Seleccione Color Primario de la Marca')
                                                                        ->required(),
                                                                ])
                                                        ])
                                    ])

                    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->emptyStateDescription('Una vez que creas tu primera marca, aparecerá aquí.')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre de la Marca')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('Web de la Marca')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ColorColumn::make('primary_hex')
                    ->label('Color Primario')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_visible')
                    ->boolean()
                    ->label('Visible')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->searchable()
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
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
