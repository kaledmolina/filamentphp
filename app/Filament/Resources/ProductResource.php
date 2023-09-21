<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Enums\ProductTypeEnum;
use Filament\Resources\Resource;
use Filament\Forms\FormsComponent;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
                                                ->live(onBlur:true)
                                                ->unique()
                                                ->afterStateUpdated(function (String $operation,  $state, Forms\Set $set) {
                                                     if ($operation !== 'create' ) {
                                                        return;
                                                     }
                                                        $set('slug', Str::slug($state));
                                                })
                                                ->placeholder('Nombre del producto'),
                                                Forms\Components\TextInput::make('slug')
                                                ->label('slug')
                                                ->disabled()
                                                ->dehydrated()
                                                ->required()
                                                ->unique(Product::class, 'slug', ignoreRecord: true),
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
                                                ->label('SKU (Numero de stock unico)')
                                                ->unique()
                                                ->autofocus()
                                                ->required(),
                                        Forms\Components\TextInput::make('price')
                                                ->label('Precio')
                                                ->numeric()
                                                ->rules(['min:0', 'regex:/^\d+$/'])
                                                ->autofocus()
                                                ->required(),
                                        Forms\Components\TextInput::make('quantity')
                                                ->label('Cantidad')
                                                ->autofocus()
                                                ->required()
                                                ->numeric()
                                                ->minValue(0)
                                                ->maxValue(1000),
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
                                            ->helperText('Si el producto no es visible, no se mostrará en la tienda')
                                            ->default(true),
                                        Forms\Components\Toggle::make('is_featured')
                                            ->label('¿Es destacado?')
                                            ->helperText('Si el producto es destacado, se mostrará en la página de inicio')
                                            ->default(false),
                                        Forms\Components\DatePicker::make('published_at')
                                            ->label('Fecha de publicación')
                                            ->default(now())
                                            ->required(),  
                                             
                                ]),
                            Forms\Components\Section::make( 'Imagen del producto')

                                ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->directory('form-attachments')
                                            ->preserveFilenames()
                                            ->label('Imagen')
                                            ->image() 
                                            ->imageEditor()
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
        ->emptyStateDescription('Una vez que creas tu primer producto, aparecerá aquí.')
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('Imagen')
                    ->square(),
                Tables\Columns\TextColumn::make('name')->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')->label('Marca')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_visible')->boolean()->label('¿Es visible?')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')->label('Precio')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')->label('Cantidad')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')->label('Fecha de publicación')
                    ->date( )
                    ->toggleable(),
                tables\Columns\TextColumn::make('type')->label('Tipo'),
            ])
            
            ->filters([
                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label('¿Es visible?')
                    ->options([
                        'yes' => 'Si',
                        'no' => 'No',
                    ]),
                Tables\Filters\SelectFilter::make('brand')
                     
                    ->relationship('brand', 'name')
                    -> label('Marca')
            ])
            ->actions([
                tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
