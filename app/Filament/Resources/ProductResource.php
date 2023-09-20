<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\ProductTypeEnum;
use Filament\Resources\Resource;
use Filament\Forms\FormsComponent;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    
    protected static ?string $navigationLabel = 'Mis Productos';

    protected static ?string $navigationGroup = 'Tienda';

    protected static ?string $modelLabel = 'Producto';

    protected static ?string $pluralModelLabel = 'Mis productos';


    public static function form(Form $form): Form
    {
        return $form
            

            ->schema([

                    Forms\Components\Group::make()

                            ->schema([

                                Forms\Components\Section::make( 'Información del producto')

                                    ->schema([
                                            Forms\Components\TextInput::make('name')
                                                ->label('Nombre')
                                                ->autofocus()
                                                ->required()
                                                ->placeholder('Nombre del producto'),
                                                Forms\Components\TextInput::make('slug')
                                                ->label('slug')
                                                ->autofocus()
                                                ->required(),
                                                Forms\Components\MarkdownEditor::make('description')
                                                ->label('Descripción')
                                                ->autofocus()
                                                ->required()
                                                ->placeholder('Descripción del producto')
                                            ->columnSpan('full')   

                                         
                                ])->columns(2),  
                               
                                
                                Forms\Components\Section::make( 'Precio e inventario del producto')

                                    ->schema([
                                        Forms\Components\TextInput::make('sku')
                                                ->label('sku')
                                                ->autofocus()
                                                ->required(),
                                        Forms\Components\TextInput::make('price')
                                                ->label('Precio')
                                                ->autofocus()
                                                ->required(),
                                        Forms\Components\TextInput::make('quantity')
                                                ->label('Cantidad')
                                                ->autofocus()
                                                ->required(),
                                        Forms\Components\Select::make('type')
                                                ->label('Tipo')
                                                ->options([
                                                    'deliverable' => ProductTypeEnum::DELIVERABLE->value,
                                                    'downloadable' => ProductTypeEnum::DOWNLOADABLE->value,
                                                    ])
                                                ->required(),                      
                                ])->columns(2),
                                        
                    ]),
                
                Forms\Components\Group::make()

                        ->schema([

                            Forms\Components\Section::make( 'Estado del producto')

                                ->schema([
                                        Forms\Components\Toggle::make('is_visible')
                                            ->label('¿Es visible?')
                                            ->default(false),
                                        Forms\Components\Toggle::make('is_featured')
                                            ->label('¿Es destacado?')
                                            ->default(false),
                                        Forms\Components\DatePicker::make('published_at')
                                            ->label('Fecha de publicación')
                                            ->default(now())
                                            ->required(),  
                                             
                                ]),
                            Forms\Components\Section::make( 'Imagen del producto')

                                ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->label('Imagen')
                                            ->image() 
                                            ->required(), 
                                             
                                  ])->collapsible(),

                                  Forms\Components\Section::make( 'Asociar producto a una marca')

                                  ->schema([
                                  Forms\Components\Select::make('brand_id')
                                                ->label('Marca')
                                                ->placeholder('Seleccione una marca')
                                                ->relationship('brand', 'name')
                                                ->required(),  
                                            ])
                            
                                    
                ]) 
             ]);
            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('Imagen')->square(),
                Tables\Columns\TextColumn::make('name')->label('Nombre'),
                Tables\Columns\TextColumn::make('brand.name')->label('Marca'),
                Tables\Columns\IconColumn::make('is_visible')->boolean()->label('¿Es visible?'),
                Tables\Columns\TextColumn::make('price')->label('Precio'),
                Tables\Columns\TextColumn::make('quantity')->label('Cantidad'),
                Tables\Columns\TextColumn::make('published_at')->label('Fecha de publicación'),
                tables\Columns\TextColumn::make('type')->label('Tipo'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }    
}
