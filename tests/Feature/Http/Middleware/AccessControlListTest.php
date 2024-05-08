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
		$token = '4|3kE7wP1EMfTXqxV1IraTFkBn9AS0w6CslfRDPNpe67794b99';

		$response = $this->json('get', '/api/profile', [], [
			'Accept' => 'application/json',
			'Authorization' => 'Bearer ' . $token,
		]);

		match ($response->getStatusCode()) {
			200 => $response->assertOk(),
			401 => $response->assertUnauthorized(),
		};
	}
}
