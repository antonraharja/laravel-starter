<?php

namespace App\Base\ACL\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'description',
		'type',
		'content',
	];

	protected $casts = [
		'content' => 'array',
	];

	public function roles(): BelongsToMany
	{
		return $this->belongsToMany(Role::class)->withTimestamps();
	}
}
