<?php

namespace Tests\Feature\Http\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccessControlListTest extends TestCase
{
	/**
	 * A basic feature test example.
	 */
	public function test_example(): void
	{
		$token = '35|cjFkcdMS1Fxjmz2aShBMlY6xz6EFSNuzXBcMPCOQ9c3f222a';

		$response = $this->json('get', '/api/permissions', [], [
			'Accept' => 'application/json',
			'Authorization' => 'Bearer ' . $token,
		]);

		$response->assertStatus(200);
	}
}
