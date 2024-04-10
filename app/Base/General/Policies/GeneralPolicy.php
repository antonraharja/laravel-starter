<?php

namespace Base\ACL\Policies;

use Base\ACL\Facades\ACL;
use Base\General\Models\General;
use App\Models\User;

class GeneralPolicy
{
	/**
	 * Determine whether the user can view any models.
	 */
	public function viewAny(User $user): bool
	{
		return ACL::have('general.viewany');
	}

	/**
	 * Determine whether the user can view the model.
	 */
	public function view(User $user, General $general): bool
	{
		return ACL::have('general.view');
	}

	/**
	 * Determine whether the user can create models.
	 */
	public function create(User $user): bool
	{
		return ACL::have('general.create');
	}

	/**
	 * Determine whether the user can update the model.
	 */
	public function update(User $user, General $general): bool
	{
		return ACL::have('general.update');
	}

	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, General $general): bool
	{
		return ACL::have('general.delete');
	}

	/**
	 * Determine whether the user can restore the model.
	 */
	public function restore(User $user, General $general): bool
	{
		return ACL::have('general.restore');
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, General $general): bool
	{
		return ACL::have('general.forcedelete');
	}
}
