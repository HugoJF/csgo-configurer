<?php

namespace App\Http\Controllers;

use App\Bundle;
use App\Server;
use App\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Kris\LaravelFormBuilder\FormBuilder;

class BundleController extends Controller
{
	public function create(FormBuilder $formBuilder, $type = null, $id = null)
	{
		$form = $formBuilder->create('App\Forms\BundleForm', [
			'method' => 'POST',
			'url'    => route('bundle.store', [$type, $id]),
		]);

		switch ($type) {
			case null:
			case 'user':
				$title = 'User Bundle Form';
				break;
			case 'template':
				$title = 'Template Bundle Form';
				break;
			case 'server':
				$title = 'Server Bundle Form';
				break;
			default:
				$title = 'Unknown Bundle Form';
		}

		return view('generics.form', [
			'title'       => $title,
			'form'        => $form,
			'type'        => $type,
			'id'          => $id,
			'submit_text' => 'Create',
		]);
	}

	public function store($type = null, $id = null)
	{

		$bundle = Bundle::make();

		$bundle->fill(Input::all());

		if ($type == 'user' || $type == null) {
			$bundle->owner()->associate(Auth::user());
		} else if ($type == 'server') {
			$bundle->owner()->associate(Server::where('id', $id)->first());
		} else if ($type == 'template') {
			$bundle->owner()->associate(Template::where('slug', $id)->first());
		}

		$bundle->save();

		return redirect()->route('bundle.show', $bundle);
	}

	public function index()
	{
		$bundles = Bundle::all();

		return view('bundle.index', [
			'bundles' => $bundles,
		]);
	}

	public function delete(Bundle $bundle)
	{
		flash()->success("Bundle {$bundle->name} deleted!");

		$bundle->delete();

		return redirect()->back();
	}

	public function show(Bundle $bundle)
	{

		$bundle->load('constants');

		return view('bundle.show', [
			'bundle' => $bundle,
		]);
	}

	public function edit(FormBuilder $formBuilder, Bundle $bundle)
	{
		$form = $formBuilder->create('App\Forms\BundleForm', [
			'method' => 'PATCH',
			'url'    => route('bundle.update', $bundle),
			'model'  => $bundle,
		]);

		return view('generics.form', [
			'title'       => 'Bundle Update Form',
			'form'        => $form,
			'submit_text' => 'Update',
		]);
	}

	public function update(Request $request, Bundle $bundle)
	{
		$bundle->fill($request->all());

		$bundle->save();

		flash()->success("Bundle {$bundle->name} updated successfully!'");

		return redirect()->route('bundle.show', $bundle);
	}
}
