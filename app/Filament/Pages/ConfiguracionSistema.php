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
            'nombre_institucion'   => $config->nombre_institucion,
            'logo'                 => $config->logo,
            'votacion_activa'      => $config->votacion_activa,
            'requerir_password'    => $config->requerir_password,
        ];

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Fila 1: Logo + nombre
                Forms\Components\Grid::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo de la institución')
                            ->image()
                            ->directory('logos')
                            ->avatar()
                            ->imageEditor()
                            ->imagePreviewHeight('150')
                            ->required(),

                        Forms\Components\TextInput::make('nombre_institucion')
                            ->label('Nombre de la institución')
                            ->required()
                            ->maxLength(255),
                    ]),

                // Fila 2: Selectores uno debajo del otro
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
