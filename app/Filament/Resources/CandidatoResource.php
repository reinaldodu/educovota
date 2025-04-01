<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CandidatoResource\Pages;
use App\Filament\Resources\CandidatoResource\RelationManagers;
use App\Models\Candidato;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CandidatoResource extends Resource
{
    protected static ?string $model = Candidato::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //agregar imagen
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
            ->columns([
                Tables\Columns\TextColumn::make('nombres')
                    ->searchable(),
                Tables\Columns\TextColumn::make('apellidos')
                    ->searchable(),
                //mostrar imagen como avatar
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
                    ->size(50)
                    ->circular(),
                Tables\Columns\TextColumn::make('categoria.nombre')
                    ->label('Categoria')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListCandidatos::route('/'),
            'create' => Pages\CreateCandidato::route('/create'),
            'edit' => Pages\EditCandidato::route('/{record}/edit'),
        ];
    }
}
