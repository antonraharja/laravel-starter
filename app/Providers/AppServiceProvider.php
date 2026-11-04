<?php

namespace App\Providers;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		// Filament v4 changed these to span one column by default; existing forms rely on full width.
		Fieldset::configureUsing(fn(Fieldset $fieldset) => $fieldset->columnSpanFull());
		Grid::configureUsing(fn(Grid $grid) => $grid->columnSpanFull());
		Section::configureUsing(fn(Section $section) => $section->columnSpanFull());

		// Filament v4 defers table filters behind an apply button; keep applying them immediately.
		Table::configureUsing(fn(Table $table) => $table->deferFilters(false));

		// Filament 5.11 race: replacing the file in a single-file upload deletes the old file first, which
		// empties the state and makes the component reset its file list, dropping the new file before it
		// uploads. Saving then clears the field. Hold state syncing from the moment a user-added file
		// (FilePond origin 1) arrives; the component re-enables it when the upload finishes.
		FileUpload::configureUsing(fn(FileUpload $upload) => $upload->extraAlpineAttributes(
			fn(FileUpload $component): array => $component->isMultiple() ? [] : [
				'x-init' => "\$el.addEventListener('FilePond:addfilestart', (event) => { if (event.detail.file.origin === 1) { shouldUpdateState = false } })",
			],
		));
	}
}
