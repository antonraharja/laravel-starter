<?php

namespace App\Policies;

use App\Models\User;
use App\Base\ACL\Facades\ACL;

class UserPolicy
{
	/**
	 * Determine whether the user can view any models.
	 */
	public function viewAny(User $user): bool
	{
		return ACL::permit('user.viewany');
	}

	/**
	 * Determine whether the user can view the model.
	 */
	public function view(User $user, User $model): bool
	{
		return ACL::permit('user.view');
	}

	/**
	 * Determine whether the user can create models.
	 */
	public function create(User $user): bool
	{
		return ACL::permit('user.create');
	}

	/**
	 * Determine whether the user can update the model.
	 */
	public function update(User $user, User $model): bool
	{
		return ACL::permit('user.update');
	}

	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, User $model): bool
	{
		return ACL::permit('user.delete');
	}

	/**
	 * Determine whether the user can restore the model.
	 */
	public function restore(User $user, User $model): bool
	{
		return ACL::permit('user.restore');
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, User $model): bool
	{
		return ACL::permit('user.forcedelete');
	}
}
