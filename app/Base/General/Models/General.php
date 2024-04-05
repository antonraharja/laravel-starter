<?php

namespace Base\General\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
