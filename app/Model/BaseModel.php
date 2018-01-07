<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
	//
	public function getDateFormat()
	{
		return 'U'; //U = Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)
	}

	public function CreatedInt()
	{
		return $this->created_at->timestamp;
	}

	public function UpdatedInt()
	{
		return $this->updated_at->timestamp;
	}
}
