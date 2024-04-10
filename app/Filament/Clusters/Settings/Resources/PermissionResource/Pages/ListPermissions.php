<?php

namespace App\Filament\Clusters\Settings\Resources\PermissionResource\Pages;

use Filament\Tables;
use Filament\Actions;
use Base\ACL\Facades\ACL;
use Filament\Tables\Table;
use Base\General\Facades\General;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Clusters\Settings\Resources\PermissionResource;

class ListPermissions extends ListRecords
{
	protected static string $resource = PermissionResource::class;

	protected function getHeaderActions(): array
	{
		return [
			Actions\CreateAction::make(),
		];
	}

	public function table(Table $table): Table
	{
		return $table
			->reorderable('order_column')
			->defaultSort('order_column')
			->columns([
				TextColumn::make('name')
					->sortable()
					->searchable(),
				TextColumn::make('description')
					->sortable()
					->searchable()
					->toggleable(isToggledHiddenByDefault: true),
				SelectColumn::make('type')
					->label(__('Type'))
					->options(ACL::config()->allPermissionTypes)
					->selectablePlaceholder(false)
					->sortable()
					->searchable(),
				TagsColumn::make('content')
					->label(__('Content'))
					->sortable()
					->searchable(),
				TagsColumn::make('roles.name')
					->label(__('Roles'))
					->sortable()
					->searchable(),
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
				SelectFilter::make('type')
					->label(__('Permission type'))
					->placeholder(__('Select options'))
					->multiple()
					->options(ACL::config()->allPermissionTypes)
					->native(false),
				Filter::make('content')
					->label(__('Permission content'))
					->form([
						Select::make('content_keyword')
							->label(__('Permission content'))
							->placeholder(__('Select options'))
							->multiple()
							->options(ACL::config()->allPermissionsSelect)
					])
					->query(function (Builder $query, array $data): Builder {
						return $query
							->when(
								$data['content_keyword'],
								function (Builder $query, array $data) {
									foreach ( $data as $item ) {
										$query->orWhereJsonContains('content', $item);
									}

									return $query;
								}
							);
					})->indicateUsing(function (array $data): array {
						$indicators = [];
						if ($data['content_keyword'] ?? null) {
							$items = '';
							foreach ( $data['content_keyword'] as $item ) {
								$items .= $item . ' ';
							}
							$items = trim($items);

							$indicators[] = Indicator::make(__('Permission content') . ': ' . $items);
						}

						return $indicators;
					}),
			])
			->actions([
				Tables\Actions\DeleteAction::make()
					->label('')
					->tooltip(_('Delete')),
				Tables\Actions\EditAction::make()
					->label('')
					->tooltip(__('Edit')),
			])
			->bulkActions([
				Tables\Actions\BulkActionGroup::make([
					Tables\Actions\DeleteBulkAction::make(),
				]),
			]);
	}
}
