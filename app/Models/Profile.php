<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Profile extends Model
{
	use HasFactory;

	protected $fillable = [
		'first_name',
		'last_name',
		'photo',
		'dob',
		'country',
		'city',
		'address',
		'bio',
		'contact'
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
