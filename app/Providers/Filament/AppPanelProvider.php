<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use APP\Filament\App\Pages\LoginEstudiante;
use App\Filament\App\Pages\Votar;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Schema;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $config = null;
		if (Schema::hasTable('configuraciones')) {
			$config = Configuracion::first();
		}
        
        return $panel
            ->favicon(asset('images/favicon.png'))
            ->darkMode(false)
            ->navigation(false)
            ->id('app')
            ->path('/')
            ->login(LoginEstudiante::class)
            ->authGuard('students')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->topbar(false)
            ->brandLogo(function () use ($config) {
                $path = storage_path('app/public/' . ($config->logo ?? ''));
                if ($config && $config->logo && file_exists($path)) {
                    return asset('storage/' . $config->logo);
                }

                // Si no hay logo, renderiza el SVG inline
                return view('filament.logo-default');
            })
            ->brandLogoHeight('4rem')
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                Votar::class,
                // Pages\Dashboard::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
