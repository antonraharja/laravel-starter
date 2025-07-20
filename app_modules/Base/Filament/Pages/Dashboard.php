<?php

namespace Base\Filament\Pages;

use Illuminate\Contracts\Support\Htmlable;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;

class Dashboard extends BaseDashboard
{
	use HasFiltersAction;

	public function getTitle(): string|Htmlable
	{
		return __('Dashboard');
	}
}