<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Installation extends Model
{
	protected $fillable = [
		'name', 'description',
	];

	public function server()
	{
		return $this->hasOne('App\Server');
	}

	public function templates()
	{
		return $this->belongsToMany('App\Template')->withPivot('bundle_id')->using('App\InstallationTemplate');
	}

	public function selections()
	{
		return $this->hasMany('App\Selection');
	}

	public function files()
	{
		$files = [];

		foreach ($this->templates as $template) {
			foreach ($template->files as $file) {
				array_push($files, $file);
			}
		}

		return $files;
	}
}
