<?php

namespace App;

use Faker\Provider\Base;
use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends BaseModel
{
	//
	protected $fillable = [
		'user_id', 'token',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
