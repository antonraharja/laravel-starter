<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Base\General\Facades\General;
use Illuminate\Support\Facades\App;
use Filament\Support\Enums\MaxWidth;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Base\Filament\Pages\Auth\CustomLogin;
use Base\Filament\Pages\Auth\CustomRegister;
use Base\Filament\Pages\Auth\CustomEditProfile;
use Base\Filament\Pages\Auth\PasswordReset\CustomRequestPasswordReset;
use Base\Filament\Pages\Auth\EmailVerification\CustomEmailVerificationPrompt;

class AdminPanelProvider extends PanelProvider
{
	public function panel(Panel $panel): Panel
	{
		if (App::runningInConsole()) {

			return $panel
				->default()
				->id('admin')
				->path('admin')
				->login(CustomLogin::class);
		}

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
			->sidebarCollapsibleOnDesktop()
			->viteTheme('resources/css/filament/admin/theme.css')
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

			->discoverClusters(in: base_path('app_modules/Base/Filament/Clusters'), for: 'Base\\Filament\\Clusters')
			->discoverResources(in: base_path('app_modules/Base/Filament/Resources'), for: 'Base\\Filament\\Resources')
			->discoverPages(in: base_path('app_modules/Base/Filament/Pages'), for: 'Base\\Filament\\Pages')
			->discoverWidgets(in: base_path('app_modules/Base/Filament/Widgets'), for: 'Base\\Filament\\Widgets')

			->resources([
				// Resources\PhonebookResource::class,
			])
			->pages([
				// Pages\Dashboard::class,
			])
			->widgets([
				Widgets\AccountWidget::class,
				// Widgets\FilamentInfoWidget::class,
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
