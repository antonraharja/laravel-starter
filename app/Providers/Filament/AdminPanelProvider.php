<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Base\General\Facades\General;
use Filament\Support\Enums\MaxWidth;
use App\Filament\Pages\Auth\CustomLogin;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Pages\Auth\CustomRegister;
use App\Filament\Pages\Auth\CustomEditProfile;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Pages\Auth\PasswordReset\CustomRequestPasswordReset;
use App\Filament\Pages\Auth\EmailVerification\CustomEmailVerificationPrompt;

class AdminPanelProvider extends PanelProvider
{
	public function panel(Panel $panel): Panel
	{
		return $panel
			->default()
			->id('admin')
			->path('admin')
			->login(CustomLogin::class)
			->profile(CustomEditProfile::class, isSimple: true)
			->registration(General::getEnableRegister() ? CustomRegister::class : null)
			->passwordReset(General::getEnablePasswordReset() ? CustomRequestPasswordReset::class : null)
			->requiresEmailVerification(General::getEnableEmailVerification())
			->emailVerification(General::getEnableEmailVerification() ? CustomEmailVerificationPrompt::class : null)
			->loginRouteSlug('login')
			->registrationRouteSlug('register')
			->passwordResetRoutePrefix('password-reset')
			->passwordResetRequestRouteSlug('request')
			->passwordResetRouteSlug('reset')
			->emailVerificationRoutePrefix('email-verification')
			->emailVerificationPromptRouteSlug('prompt')
			->emailVerificationRouteSlug('verify')
			->colors([
				'primary' => General::getPrimaryColorScheme(),
				'danger' => General::getDangerColorScheme(),
				'gray' => General::getGrayColorScheme(),
				'info' => General::getInfoColorScheme(),
				'success' => General::getSuccessColorScheme(),
				'warning' => General::getWarningColorScheme(),
			])
			->brandName(General::getBrandName())
			->brandLogo(General::getBrandLogo())
			->favicon(General::getFavico())
			->maxContentWidth(MaxWidth::Full)
			->topNavigation(!General::getDisableTopNavigation())
			->revealablePasswords(General::getRevealablePasswords())
			->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
			->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
			->pages([
				// Pages\Dashboard::class,
			])
			->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
			->widgets([
				Widgets\AccountWidget::class,
				// Widgets\FilamentInfoWidget::class,
			])
			->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
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
