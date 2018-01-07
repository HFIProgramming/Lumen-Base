<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActionLog extends BaseModel
{
	//
	protected $fillable = [
		'user_id', 'model_name', 'action', 'description',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
