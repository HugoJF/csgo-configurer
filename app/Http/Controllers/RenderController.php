<?php

namespace App\Http\Controllers;

use App\Render;
use Illuminate\Http\Request;

class RenderController extends Controller
{
	public function show(Render $render)
	{
		return view('render.show', [
			'render' => $render,
			'breadcrumb' => $render->server->showBreadcrumb()->addCurrent('Render logs'),
		]);
	}
}
