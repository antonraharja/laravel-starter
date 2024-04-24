<?php

namespace Base\User\Models;

use Base\ACL\Models\Role;
use Base\ACL\Traits\HasACL;
use Base\User\Models\Profile;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\HasName;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail, HasAvatar, HasName
{
	use HasApiTokens;
	use HasFactory;
	use Notifiable;
	use HasACL;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'username',
		'email',
		'password',
		'email_verified_at',
		'timezone',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
		'password' => 'hashed',
	];

	public function profile(): HasOne
	{
		return $this->hasOne(Profile::class);
	}

	public function roles(): BelongsToMany
	{
		return $this->belongsToMany(Role::class)->withTimestamps();
	}

	public function getFilamentAvatarUrl(): ?string
	{
		if (isset($this->profile->photo) && Storage::disk('local')->exists($this->profile->photo)) {
			return asset('storage/' . $this->profile->photo);
		} else {
			return null;
		}
	}

	public function getFilamentName(): string
	{
		$firstName = isset($this->profile->first_name) ? $this->profile->first_name : null;
		$lastName = isset($this->profile->last_name) ? $this->profile->last_name : null;

		$name = null;

		if (isset($firstName)) {
			$name .= $firstName;
		}

		if (isset($lastName)) {
			$name .= " " . $lastName;
		}

		return $name ?? $this->username;
	}
}
