<?php

namespace Base\ACL\Policies;

use Base\ACL\Facades\ACL;
use Base\ACL\Models\Role;
use App\Models\User;

class RolePolicy
{
	/**
	 * Determine whether the user can view any models.
	 */
	public function viewAny(User $user): bool
	{
		return ACL::permit('role.viewany');
	}

	/**
	 * Determine whether the user can view the model.
	 */
	public function view(User $user, Role $role): bool
	{
		return ACL::permit('role.view');
	}

	/**
	 * Determine whether the user can create models.
	 */
	public function create(User $user): bool
	{
		return ACL::permit('role.create');
	}

	/**
	 * Determine whether the user can update the model.
	 */
	public function update(User $user, Role $role): bool
	{
		return ACL::permit('role.update');
	}

	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, Role $role): bool
	{
		return ACL::permit('role.delete');
	}

	/**
	 * Determine whether the user can restore the model.
	 */
	public function restore(User $user, Role $role): bool
	{
		return ACL::permit('role.restore');
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, Role $role): bool
	{
		return ACL::permit('role.forcedelete');
	}
}
