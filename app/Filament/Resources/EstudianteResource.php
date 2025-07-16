<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EstudianteResource\Pages;
use App\Models\Estudiante;
use App\Models\Voto;
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
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Columns\IconColumn;

class EstudianteResource extends Resource
{
    protected static ?string $model = Estudiante::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nombres')->required()->maxLength(255),
            Forms\Components\TextInput::make('apellidos')->required()->maxLength(255),
            Forms\Components\TextInput::make('documento')->required()->unique(ignoreRecord: true)->maxLength(255),
            Forms\Components\TextInput::make('password')
                ->password()->maxLength(255)
                ->dehydrated(fn ($state) => !empty($state))
                ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
            Forms\Components\Select::make('grado_id')
                ->relationship('grado', 'nombre', fn ($query) => $query->orderBy('id'))
                ->required()->preload()->searchable(),
        ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('nombres')->searchable(),
                Tables\Columns\TextColumn::make('apellidos')->searchable(),
                Tables\Columns\TextColumn::make('documento')->searchable(),
                Tables\Columns\TextColumn::make('grado.nombre')->label('Grado')->sortable()->searchable(),
                IconColumn::make('ha_votado')
                    ->label('Votó')
                    ->getStateUsing(fn ($record) => Voto::where('estudiante_id', $record->id)->exists())
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->trueColor('success')
                    ->tooltip(function ($record) {
                        $voto = \App\Models\Voto::where('estudiante_id', $record->id)->latest()->first();
                        return $voto
                            ? 'Votó el ' . $voto->created_at->format('d/m/Y \a \l\a\s h:i A')
                            : null;
                    })
                    ->alignCenter(),
            ])
            ->filters([
                TernaryFilter::make('voto')
                    ->label('Estado de voto')
                    ->placeholder('Todos')
                    ->trueLabel('Ya votaron')
                    ->falseLabel('No han votado')
                    ->queries(
                        true: fn ($query) => $query->whereIn('id', function ($subquery) {
                            $subquery->select('estudiante_id')->from('votos');
                        }),
                        false: fn ($query) => $query->whereNotIn('id', function ($subquery) {
                            $subquery->select('estudiante_id')->from('votos');
                        }),
                        blank: fn ($query) => $query,
                    ),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Action::make('importar-estudiantes')
                    ->label('Importar CSV')->color('gray')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->form([
                        FileUpload::make('archivo')
                            ->label('Subir archivo CSV')
                            ->disk('local')->directory('importaciones')
                            ->acceptedFileTypes(['text/csv', 'application/csv'])
                            ->rules(['mimes:csv'])
                            ->required(),
                    ])
                    ->modalWidth('md')->modalHeading('Importar estudiantes desde archivo')
                    ->action(function (array $data) {
                        $disk = 'local';
                        $ruta = Storage::disk($disk)->path($data['archivo']);
                        $resultado = (new ImportarEstudiantes())->ejecutar($ruta);
                        
                        if ($resultado['errores']->isNotEmpty()) {
                            $err = $resultado['errores']->first();
                            Notification::make()->title('Error en la importación')
                                ->body("Fila {$err['fila']}: {$err['errores']}")
                                ->danger()->send();
                        } else {
                            Notification::make()->title('Importación exitosa')
                                ->success()
                                ->body("Se importaron {$resultado['importados']} registros correctamente.")
                                ->send();
                        }
                        
                        Storage::disk($disk)->delete($data['archivo']);
                    }),
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
