<?php

namespace Base\Token\Policies;

use App\Models\User;
use Base\ACL\Facades\ACL;
use Base\TOken\Models\Token;

class TokenPolicy
{
	/**
	 * Determine whether the user can view any models.
	 */
	public function viewAny(User $user): bool
	{
		return ACL::have('token.viewany');
	}

	/**
	 * Determine whether the user can view the model.
	 */
	public function view(User $user, Token $token): bool
	{
		if (!ACL::have('token.view')) {
			return false;
		}

		return $user->id === $token->tokenable_id;
	}

	/**
	 * Determine whether the user can create models.
	 */
	public function create(User $user): bool
	{
		return ACL::have('token.create');
	}

	/**
	 * Determine whether the user can update the model.
	 */
	public function update(User $user, Token $token): bool
	{
		if (!ACL::have('token.update')) {
			return false;
		}

		return $user->id === $token->tokenable_id;
	}

	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, Token $token): bool
	{
		if (!ACL::have('token.delete')) {
			return false;
		}

		return $user->id === $token->tokenable_id;
	}

	/**
	 * Determine whether the user can restore the model.
	 */
	public function restore(User $user, Token $token): bool
	{
		if (!ACL::have('token.restore')) {
			return false;
		}

		return $user->id === $token->tokenable_id;
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, Token $token): bool
	{
		if (!ACL::have('token.forcedelete')) {
			return false;
		}

		return $user->id === $token->tokenable_id;
	}
}
