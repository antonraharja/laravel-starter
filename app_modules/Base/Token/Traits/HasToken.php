<?php

namespace Base\Token\Traits;

use Exception;
use Filament\Forms\Form;
use Base\Token\Result;
use Illuminate\Support\Carbon;

trait HasToken
{
	private Result $newToken;

	public function createToken(Form $form)
	{
		$this->newToken = new Result;

		if (!auth()->user()->have('token.create')) {
			$this->newToken->setResult(401);

			return $this;
		}

		try {
			$data = $form->getState();

			$data['tokenable_id'] = $data['tokenable_id'] ?? auth()->user()->id;

			$data['abilities'] = isset($data['abilities']) && is_array($data['abilities']) ? $data['abilities'] : ['*'];

			$data['expires_at'] = isset($data['expires_at']) ? Carbon::parse($data['expires_at']) : Carbon::parse('now')->addDays(60);

			if ($user = auth()->user()->find($data['tokenable_id'])) {

				$token = $user->createToken($data['name'], $data['abilities'], $data['expires_at']);

				if ($token->plainTextToken) {
					$this->newToken->setResult(200, $token->plainTextToken);

					return $this;
				}
			}

			$this->newToken->setResult(400);
		} catch (Exception $e) {
			$this->newToken->setResult(500, '', $e->getMessage());
		}

		return $this;
	}

	public function getToken(): array
	{
		return $this->newToken->getResult();
	}
}