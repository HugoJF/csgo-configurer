<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class InstallationTemplate extends Pivot
{
	public function selection()
	{
		return $this->belongsTo('App\Bundle', 'bundle_id');
	}
}
