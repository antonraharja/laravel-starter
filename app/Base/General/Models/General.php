<?php

namespace Base\General\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class General extends Model
{
	use HasFactory;

	protected $fillable = [
		'group',
		'keyword',
		'content',
	];

	// protected $casts = [
	// 	'content' => 'array',
	// ];
}
