<?php

use App\Models\User;
use Base\ACL\Facades\ACL;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum', 'acl:user.view'])->get('/profile', function (Request $request) {
	return $request->user()->profile;
});

Route::middleware('auth:sanctum')->get('/roles', function (Request $request) {
	if (!ACL::role('admin')) {
		return Response::deny('Forbidden');
	}

	$roles = 'Roles: ';
	foreach ( ACL::getRoles() as $role ) {
		$roles .= $role . ' ';
	}
	$roles = trim($roles);

	return Response::allow($roles);
});

Route::get('/permissions', function (Request $request) {
	$permissions = 'Permissions: ';
	foreach ( $request->user()->getPermissions() as $permission ) {
		$permissions .= $permission . ' ';
	}
	$permissions = trim($permissions);

	return Response::allow($permissions);
})->middleware(['auth:sanctum', 'acl:permission.viewany']);
