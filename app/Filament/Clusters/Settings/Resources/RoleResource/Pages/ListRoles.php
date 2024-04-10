<?php

namespace App\Filament\Clusters\Settings\Resources\RoleResource\Pages;

use Filament\Tables;
use Filament\Actions;
use Base\ACL\Facades\ACL;
use Filament\Tables\Table;
use Base\General\Facades\General;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Clusters\Settings\Resources\RoleResource;

class ListRoles extends ListRecords
{
	protected static string $resource = RoleResource::class;

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
				TagsColumn::make('permissions.name')
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
				SelectFilter::make('name')
					->label(__('Roles type'))
					->placeholder(__('Select options'))
					->multiple()
					->options(ACL::config()->allRolesSelect)
					->native(false),
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
