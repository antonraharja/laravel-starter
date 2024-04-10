<?php

namespace Base\ACL\Policies;

use Base\ACL\Facades\ACL;
use Base\ACL\Models\Permission;
use App\Models\User;

class PermissionPolicy
{
	/**
	 * Determine whether the user can view any models.
	 */
	public function viewAny(User $user): bool
	{
		return ACL::have('permission.viewany');
	}

	/**
	 * Determine whether the user can view the model.
	 */
	public function view(User $user, Permission $permission): bool
	{
		return ACL::have('permission.view');
	}

	/**
	 * Determine whether the user can create models.
	 */
	public function create(User $user): bool
	{
		return ACL::have('permission.create');
	}

	/**
	 * Determine whether the user can update the model.
	 */
	public function update(User $user, Permission $permission): bool
	{
		return ACL::have('permission.update');
	}

	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, Permission $permission): bool
	{
		return ACL::have('permission.delete');
	}

	/**
	 * Determine whether the user can restore the model.
	 */
	public function restore(User $user, Permission $permission): bool
	{
		return ACL::have('permission.restore');
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, Permission $permission): bool
	{
		return ACL::have('permission.forcedelete');
	}
}
