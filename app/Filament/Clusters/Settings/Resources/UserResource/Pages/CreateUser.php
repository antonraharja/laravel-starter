<?php

namespace App\Filament\Clusters\Settings\Resources\UserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Clusters\Settings\Resources\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
