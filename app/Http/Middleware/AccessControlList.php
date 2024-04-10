<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Base\ACL\Facades\ACL;
use App\Providers\RouteServiceProvider;
use Symfony\Component\HttpFoundation\Response;

class AccessControlList
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next, string $roleOrPermission): Response
	{
		if (!(ACL::role($roleOrPermission) || ACL::have($roleOrPermission))) {
			return response(['Unauthorized'], status: 401);
		}

		return $next($request);
	}
}
