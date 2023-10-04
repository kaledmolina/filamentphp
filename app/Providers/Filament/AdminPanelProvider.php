<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use Filament\Http\Middleware\Authenticate;
use pxlrbt\FilamentSpotlight\SpotlightPlugin;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use BezhanSalleh\FilamentLanguageSwitch\FilamentLanguageSwitchPlugin;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {

        return $panel
        ->plugins([
            FilamentLanguageSwitchPlugin::make()
        ])
            ->default()
            ->id('dashboard')
            ->path('dashboard')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->font('Comic Sans MS')
            ->favicon('image\favicon.ico')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->navigationItems([
                NavigationItem::make('Twitter "X"')
                    ->url('https://twitter.com/kaledmoly', shouldOpenInNewTab: true)
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->group('Contacto')
                    ->sort(2)
            ])
            ->navigationItems([
                NavigationItem::make('Github')
                    ->url('https://github.com/kaledmolina', shouldOpenInNewTab: true)
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->group('Contacto')
                    ->sort(2)
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Settings')
                    ->url('')
                    ->icon('heroicon-o-cog-6-tooth'),
                'logout' => MenuItem::make()->label('Salir')
            ])
            ->plugins([
                SpotlightPlugin::make()
            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
