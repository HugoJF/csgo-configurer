<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
	// Plugin types
	public const TYPE_RENDERABLE = 'renderable';
	public const TYPE_STATIC = 'static';

	// Server types
	public const TYPE_RENDER = 'render';
	public const TYPE_BACKUP = 'backup';
	public const TYPE_SYNC = 'sync';

	protected $fillable = [
		'path', 'type',
	];

	public function showBreadcrumb()
	{
		//TODO Fix
		return $this->owner->showBreadcrumb();
	}


	public function scopeBackup(Builder $query)
	{
		return $query->where('type', File::TYPE_BACKUP);
	}

	public function scopeSynced(Builder $query)
	{
		return $query->where('type', File::TYPE_SYNC);
	}

	public function scopeRendered(Builder $query)
	{
		return $query->where('type', File::TYPE_RENDER);
	}


	public function owner()
	{
		return $this->morphTo();
	}

	public function fieldLists()
	{
		return $this->hasMany('App\FieldList');
	}

	public function isRenderable()
	{
		return $this->type == File::TYPE_RENDERABLE;
	}

	public function isStatic()
	{
		return $this->type == File::TYPE_STATIC;
	}

	public function isRender()
	{
		return $this->type == File::TYPE_RENDER;
	}

	public function isBackup()
	{
		return $this->type == File::TYPE_BACKUP;
	}

	public function isSync()
	{
		return $this->type == File::TYPE_SYNC;
	}
}
