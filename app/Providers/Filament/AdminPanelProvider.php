<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Pemohons\PemohonResource;
use App\Filament\Resources\PromotionApplications\PromotionApplicationResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Pages\CustomLogin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(CustomLogin::class)
            ->font('Poppins')
            ->brandName('eTalent@Admin')
            ->authGuard('admin')
            ->brandLogo(asset('images/logo-university.png'))
            ->brandLogoHeight('120px')
            ->colors([
                'primary' => '#702963',
            ])
            ->resources([
                PemohonResource::class,
                PromotionApplicationResource::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
