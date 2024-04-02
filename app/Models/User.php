<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\API;
use Base\ACL\Models\Role;
use Base\ACL\Traits\HasACL;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\HasName;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements HasAvatar, HasName
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

	// public function tokens(): HasMany
	// {
	// 	return $this->hasMany(API::class, 'tokenable_id');
	// }

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
		return "{$this->profile->first_name}" . $this->profile->last_name ? " " . "{$this->profile->last_name}" : "";
	}
}
