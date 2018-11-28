<?php

namespace App;

use App\Classes\SmartLog;
use Illuminate\Database\Eloquent\Model;

class Synchronization extends Model
{
	public function server()
	{
		return $this->belongsTo('App\Server');
	}

	public function getLogsAttribute($serialized)
	{
		$logs = new SmartLog();

		$logs->jsonDeserialize($serialized);

		return $logs;
	}

	public function setLogsAttribute($value)
	{
		$this->attributes['logs'] = json_encode($value);
	}
}
