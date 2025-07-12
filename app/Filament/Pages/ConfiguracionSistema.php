<?php

namespace App\Filament\Pages;

use App\Models\Configuracion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class ConfiguracionSistema extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?int $navigationSort = 30;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Configuración';
    protected static ?string $title = 'Configuración General';
    protected static string $view = 'filament.pages.configuracion';

    public ?array $data = [];

    public function mount(): void
    {
        $config = Configuracion::getInstance();

        $this->data = [
            'nombre_institucion'      => $config->nombre_institucion,
            'descripcion_votaciones'  => $config->descripcion_votaciones,
            'logo'                    => $config->logo,
            'votacion_activa'         => $config->votacion_activa,
            'requerir_password'       => $config->requerir_password,
        ];

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Fila 1: Logo + campos al frente
                Forms\Components\Grid::make()
                    ->columns(3)
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo de la institución')
                            ->image()
                            ->directory('logos')
                            ->avatar()
                            ->imageEditor()
                            ->imagePreviewHeight('150')
                            ->columnSpan(1)
                            ->required(),

                        Forms\Components\Grid::make()
                            ->columns(1)
                            ->columnSpan(2)
                            ->schema([
                                Forms\Components\TextInput::make('nombre_institucion')
                                    ->label('Nombre de la institución')
                                    ->required()
                                    ->maxLength(100),

                                Forms\Components\TextInput::make('descripcion_votaciones')
                                    ->label('Descripción de las votaciones')
                                    ->maxLength(100)
                                    ->nullable(),
                            ]),
                    ]),

                Forms\Components\Toggle::make('votacion_activa')
                    ->label('¿Sistema de votación activo?')
                    ->onColor('success')
                    ->offColor('danger')
                    ->inline(false),

                Forms\Components\Toggle::make('requerir_password')
                    ->label('¿Requiere contraseña para votar?')
                    ->onColor('success')
                    ->offColor('danger')
                    ->inline(false),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $config = Configuracion::getInstance();

        if ($config->logo && $data['logo'] !== $config->logo) {
            Storage::disk('public')->delete($config->logo);
        }

        $config->update($data);

        Notification::make()
            ->title('Configuración actualizada')
            ->success()
            ->body('Los cambios se han guardado correctamente.')
            ->send();
    }
}
