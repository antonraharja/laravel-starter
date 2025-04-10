<?php

namespace Base\User\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
