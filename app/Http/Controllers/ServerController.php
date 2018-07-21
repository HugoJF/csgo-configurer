<?php

namespace App\Http\Controllers;

use App\Classes\VariableHandler;
use App\Events\ServerRenderRequest;
use App\Events\ServerSynchronizationRequest;
use App\File;
use App\Installation;
use App\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Kris\LaravelFormBuilder\FormBuilder;

class ServerController extends Controller
{
	public function index()
	{
		$servers = Auth::user()->servers;

		return view('server.index', [
			'servers'    => $servers,
			'breadcrumb' => Installation::indexBreadcrumb(),
		]);
	}

	public function sync(Server $server)
	{
		event(new ServerSynchronizationRequest(Auth::user(), $server));

		flash()->success('Server Synchronization requested!');

		return redirect()->back();
	}

	public function render(Server $server)
	{
		event(new ServerRenderRequest(Auth::user(), $server));

		flash()->success('Server Rendering requested!');

		return redirect()->back();
	}

	public function renderConfig(Server $server)
	{
		return view('pre', [
			'title'     => "{$server->name} - Rendering Config",
			'code'   => json_encode($server->renderConfig()),
			'breadcrumb' => $server->showBreadcrumb()->addCurrent('Rendering config'),
		]);
	}

	public function show(Server $server)
	{
		return view('server.show', [
			'server'     => $server,
			'breadcrumb' => $server->showBreadcrumb(),
		]);
	}

	public function edit(FormBuilder $formBuilder, Server $server)
	{
		$form = $formBuilder->create('App\Forms\ServerForm', [
			'method' => 'PATCH',
			'route'  => ['server.update', $server],
			'model'  => $server,
		], [
			'selected' => $server->installation ? $server->installation->id : 0,
		]);

		return view('generics.form', [
			'title'       => 'Editing Server',
			'form'        => $form,
			'submit_text' => 'Update Server',
			'breadcrumbs' => $server->showBreadcrumb()->addCurrent('Editing new server'),
		]);
	}

	public function update(Request $request, Server $server)
	{
		$server->fill($request->all());

		$server->installation()->associate(Installation::find($request->input('installation_id')));

		$server->save();

		return redirect()->route('server.show', $server);
	}

	public function create(FormBuilder $formBuilder)
	{
		$form = $formBuilder->create('App\Forms\ServerForm', [
			'method' => 'POST',
			'url'    => route('server.store'),
		]);

		return view('generics.form', [
			'title'       => 'Server Form',
			'form'        => $form,
			'submit_text' => 'Create Server',
			'breadcrumbs' => Server::indexBreadcrumb()->addCurrent('Creating new server'),
		]);
	}

	public function store(Request $request)
	{
		$server = Server::make();

		$server->fill($request->all());

		$server->installation()->associate(Installation::find($request->input('installation_id')));

		$server->user()->associate(Auth::user());

		$server->save();

		return redirect()->route('server.show', $server);
	}

	public function delete(Server $server)
	{
		$deleted = $server->delete();

		if ($deleted) {
			flash()->success('Server deleted!');
		} else {
			flash()->error('Server could not be deleted!');
		}

		return redirect()->back();
	}
}
