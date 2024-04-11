<?php

namespace Base\Registry\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Registry extends Model
{
	use HasFactory;

	protected $fillable = [
		'group',
		'keyword',
		'content',
	];
}
