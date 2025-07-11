<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EstudianteResource\Pages;
use App\Models\Estudiante;
use App\Actions\ImportarEstudiantes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

class EstudianteResource extends Resource
{
    protected static ?string $model = Estudiante::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombres')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('apellidos')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('documento')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->maxLength(255)
                    ->dehydrated(fn ($state) => !empty($state))
                    ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
                Forms\Components\Select::make('grado_id')
                    ->relationship('grado', 'nombre', fn ($query) => $query->orderBy('id'))
                    ->required()
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombres')->searchable(),
                Tables\Columns\TextColumn::make('apellidos')->searchable(),
                Tables\Columns\TextColumn::make('documento')->searchable(),
                Tables\Columns\TextColumn::make('grado.nombre')
                    ->label('Grado')
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
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Action::make('importar-estudiantes')
                    ->label('Importar CSV')
                    ->color('gray')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->form([
                        FileUpload::make('archivo')
                            ->label('Subir archivo CSV')
                            ->disk('local')
                            ->directory('importaciones')
                            ->acceptedFileTypes([
                                'text/csv',
                                'application/csv',
                            ])
                            ->rules(['mimes:csv']) // Validar tipos de archivo en el servidor
                            ->required(),
                    ])
                    ->modalWidth('md')
                    ->modalHeading('Importar estudiantes desde archivo')
                    ->action(function (array $data) {
                        $disk = 'local';
                        $ruta = Storage::disk($disk)->path($data['archivo']);
                        $importador = new ImportarEstudiantes();
                        $resultado = $importador->ejecutar($ruta);

                        if ($resultado['errores']->isNotEmpty()) {
                            $primerError = $resultado['errores']->first();
                            $mensaje = "Fila {$primerError['fila']}: {$primerError['errores']}";

                            Notification::make()
                                ->title('Error en la importación')
                                ->body($mensaje)
                                ->danger()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Importación exitosa')
                                ->success()
                                ->body("Se importaron {$resultado['importados']} registros correctamente.")
                                ->send();
                        }
                        // Eliminar el archivo después de la importación
                        Storage::disk($disk)->delete($data['archivo']);
                    })
            ]);
    }

    public static function getRelations(): array 
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEstudiantes::route('/'),
            'create' => Pages\CreateEstudiante::route('/create'),
            'edit' => Pages\EditEstudiante::route('/{record}/edit'),
        ];
    }
}