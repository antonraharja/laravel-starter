<?php

namespace App\Filament\Clusters\Settings\Resources\UserResource\Pages;

use Carbon\Carbon;
use App\Models\User;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;
use Base\General\Facades\General;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Clusters\Settings\Resources\UserResource;

class ListUsers extends ListRecords
{
	protected static string $resource = UserResource::class;

	protected function getHeaderActions(): array
	{
		return [
			Actions\CreateAction::make()
				->model(User::class)
				->form(CreateUser::getCreateForm())
				->modal()
				->mutateFormDataUsing(function (array $data): array {
					return CreateUser::mutateFormData($data);
				})
		];
	}

	public function table(Table $table): Table
	{
		return $table
			->reorderable('order_column')
			->defaultSort('order_column')
			->columns([
				ImageColumn::make('profile.photo')
					->label(''),
				TextColumn::make('profile.first_name')
					->label(__('First name'))
					->searchable()
					->sortable(),
				TextColumn::make('profile.last_name')
					->label(__('Last name'))
					->searchable()
					->sortable(),
				TextColumn::make('username')
					->searchable()
					->sortable(),
				TextColumn::make('email')
					->searchable()
					->sortable(),
				TextColumn::make('roles.name')
					->searchable()
					->sortable()
					->toggleable(isToggledHiddenByDefault: true),
				TextColumn::make('email_verified_at')
					->label(__('Verified'))
					->dateTime()
					->timezone(General::getTimezone())
					->searchable()
					->toggleable(isToggledHiddenByDefault: true),
				TextColumn::make('created_at')
					->label(__('Created'))
					->dateTime()
					->timezone(General::getTimezone())
					->sortable()
					->toggleable(isToggledHiddenByDefault: true),
				TextColumn::make('updated_at')
					->label(__('Updated'))
					->dateTime()
					->timezone(General::getTimezone())
					->sortable()
					->toggleable(isToggledHiddenByDefault: true),
			])
			->filters([
				SelectFilter::make('roles')
					->multiple()
					->relationship('roles', 'name')
					->searchable()
					->preload()
					->native(false),
				Filter::make('email_verified_at')
					->form([
						DatePicker::make('verified_from'),
						DatePicker::make('verified_until'),
					])
					->query(function (Builder $query, array $data): Builder {
						return $query
							->when(
								$data['verified_from'],
								fn(Builder $query, $date): Builder => $query->whereDate('email_verified_at', '>=', $date),
							)
							->when(
								$data['verified_until'],
								fn(Builder $query, $date): Builder => $query->whereDate('email_verified_at', '<=', $date),
							);
					})
					->indicateUsing(function (array $data): array {
						$indicators = [];

						if ($data['verified_from'] ?? null) {
							$indicators[] = Indicator::make(__('Verified from') . ' ' . Carbon::parse($data['verified_from'])->toFormattedDateString())
								->removeField('verified_from');
						}

						if ($data['verified_until'] ?? null) {
							$indicators[] = Indicator::make(__('Verified until') . ' ' . Carbon::parse($data['verified_until'])->toFormattedDateString())
								->removeField('verified_until');
						}

						return $indicators;
					})
			])
			->actions([
				Tables\Actions\ViewAction::make()
					->label('')
					->tooltip(__('View'))
					->infolist(ViewUser::getViewInfolist())
					->modal()
					->mutateFormDataUsing(function (array $data): array {
						return ViewUser::mutateFormData($data);
					}),
				Tables\Actions\DeleteAction::make()
					->label('')
					->tooltip(_('Delete')),
				Tables\Actions\EditAction::make()
					->label('')
					->tooltip(__('Edit'))
					->model(User::class)
					->form(EditUser::getEditForm())
					->modal()
					->mutateFormDataUsing(function (array $data): array {
						return EditUser::mutateFormData($data);
					}),
			])
			->bulkActions([
				Tables\Actions\BulkActionGroup::make([
					Tables\Actions\DeleteBulkAction::make(),
				]),
			]);
	}
}
