<?php

namespace App\Http\Controllers;

use App\Classes\VariableHandler;
use App\File;
use App\Installation;
use App\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Kris\LaravelFormBuilder\FormBuilder;

class ServerController extends Controller
{
	public function index()
	{
		$servers = Server::all();

		return view('server.index', [
			'servers' => $servers,
		]);
	}

	public function strip_first_folder($path)
	{
		$split_path = explode(DIRECTORY_SEPARATOR, $path);
		unset($split_path[0]);
		$path = implode('/', $split_path);

		return $path;
	}

	public function render(Server $server)
	{
		$installation = $server->installation;

		$files = $installation->files();

		Storage::disk('renders')->makeDirectory($server->id);

		foreach ($files as $file) {
			$this->render_file($server, $file);
		}

		flash()->success("Server {$server->name} rendered successfully!");

		return redirect()->back();
	}

	public function render_file(Server $server, File $file)
	{
		$destination_path = $server->id .  DIRECTORY_SEPARATOR . $this->strip_first_folder($file->path);


		$raw_content = Storage::disk('plugins')->get($file->path);

		if ($file->renderable) {
			$content = view(['template' => $raw_content,], $server->renderBundle())->render();
		} else {
			$content = $raw_content;
		}

		Storage::disk('renders')->put($destination_path, $content);
	}

	public function show(Server $server)
	{
		return view('server.show', [
			'server' => $server,
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
		]);
	}

	public function store(Request $request)
	{
		$server = Server::make();

		$server->fill($request->all());

		$server->installation()->associate(Installation::find($request->input('installation_id')));

		$server->save();

		return redirect()->route('server.show', $server);
	}
}
