<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CandidatoResource\Pages;
use App\Models\Candidato;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CandidatoResource extends Resource
{
    protected static ?string $model = Candidato::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('foto')
                    ->label('Foto')
                    ->image()
                    ->avatar()
                    ->maxSize(4096)
                    ->imageEditor()
                    ->disk('public')
                    ->directory('candidatos')
                    ->required(),

                Forms\Components\Select::make('categoria_id')
                    ->relationship('categoria', 'nombre')
                    ->searchable()
                    ->required()
                    ->preload(),

                Forms\Components\TextInput::make('nombres')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('apellidos')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('nombres')->searchable(),

                Tables\Columns\TextColumn::make('apellidos')->searchable(),

                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
                    ->size(50)
                    ->circular(),

                Tables\Columns\TextColumn::make('categoria.nombre')
                    ->label('Categoría')
                    ->sortable()
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
                            $anterior = Candidato::where('orden', '<', $record->orden)
                                ->orderBy('orden', 'desc')
                                ->first();

                            if ($anterior) {
                                [$record->orden, $anterior->orden] = [$anterior->orden, $record->orden];
                                $record->save();
                                $anterior->save();
                            }
                        })
                        ->visible(function ($record) {
                            $minOrden = Candidato::min('orden');
                            return $record->orden > $minOrden;
                        }),

                    Tables\Actions\Action::make('bajar')
                        ->label('⬇ Bajar')
                        ->action(function ($record) {
                            $siguiente = Candidato::where('orden', '>', $record->orden)
                                ->orderBy('orden', 'asc')
                                ->first();

                            if ($siguiente) {
                                [$record->orden, $siguiente->orden] = [$siguiente->orden, $record->orden];
                                $record->save();
                                $siguiente->save();
                            }
                        })
                        ->visible(function ($record) {
                            $maxOrden = Candidato::max('orden');
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

    /**
     * Ordenar por defecto según el campo `orden`
     */
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
            'index' => Pages\ListCandidatos::route('/'),
            'create' => Pages\CreateCandidato::route('/create'),
            'edit' => Pages\EditCandidato::route('/{record}/edit'),
        ];
    }
}
