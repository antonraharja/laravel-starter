<?php

use Livewire\Livewire;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
	return redirect(route('filament.admin.auth.login'));
})->name('home');

Route::get('/login', function () {
	return redirect(route('filament.admin.auth.login'));
})->name('login');

Livewire::setScriptRoute(function ($handle) {
	$url = parse_url(config('app.url'));
	$path = $url['path'] ?? '';

	return Route::get($path . '/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
	$url = parse_url(config('app.url'));
	$path = $url['path'] ?? '';

	return Route::post($path . '/livewire/update', $handle);
});
