<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoriaResource\Pages;
use App\Models\Categoria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CategoriaResource extends Resource
{
    protected static ?string $model = Categoria::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('descripcion')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),

                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable(),

                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),

                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('subir')
                        ->label('⬆ Subir')
                        ->action(function ($record) {
                            $anterior = Categoria::where('orden', '<', $record->orden)
                                ->orderBy('orden', 'desc')
                                ->first();

                            if ($anterior) {
                                [$record->orden, $anterior->orden] = [$anterior->orden, $record->orden];
                                $record->save();
                                $anterior->save();
                            }
                        })
                        ->visible(function ($record) {
                            $minOrden = Categoria::min('orden');
                            return $record->orden > $minOrden;
                        }),

                    Tables\Actions\Action::make('bajar')
                        ->label('⬇ Bajar')
                        ->action(function ($record) {
                            $siguiente = Categoria::where('orden', '>', $record->orden)
                                ->orderBy('orden', 'asc')
                                ->first();

                            if ($siguiente) {
                                [$record->orden, $siguiente->orden] = [$siguiente->orden, $record->orden];
                                $record->save();
                                $siguiente->save();
                            }
                        })
                        ->visible(function ($record) {
                            $maxOrden = Categoria::max('orden');
                            return $record->orden < $maxOrden;
                        }),
                ])->label('Ordenar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // 👇 Ordenar las categorías por su campo `orden` de forma predeterminada
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('orden');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategorias::route('/'),
            'create' => Pages\CreateCategoria::route('/create'),
            'edit' => Pages\EditCategoria::route('/{record}/edit'),
        ];
    }
}
