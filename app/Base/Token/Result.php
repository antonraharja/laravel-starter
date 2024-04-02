<?php

namespace App\Base\Token;

class Result
{
	private string $token;

	private int $error;

	private string $message;

	public function setResult(int $error, string $token = null, string $message = null): Result
	{
		$this->error = $error;

		switch ($error) {
			case 200:
				$this->message = $message ?? __('Copy now! The new token below will only be shown here one-time.');
				$this->token = $token;

				return $this;
			case 400:
				$this->message = $message ?? __('Fail to create new token');
				$this->token = '';

				return $this;
			case 401:
				$this->message = $message ?? __('Unauthorized');
				$this->token = '';

				return $this;
			case 500:
				$this->message = $message;
				$this->token = '';

				return $this;
		}

		$this->error = 0;
		$this->message = '';
		$this->token = '';

		return $this;
	}

	public function getResult(): array
	{
		return [
			$this->error,
			$this->message,
			$this->token,
		];
	}
}