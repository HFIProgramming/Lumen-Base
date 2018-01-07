<?php

namespace App;

class User extends BaseModel
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'username', 'email',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
	];

	public function actionLog()
	{
		return $this->hasMany(ActionLog::class);
	}

	public function passwordResetToken()
	{
		return $this->hasOne(PasswordResetToken::class);
	}
}
