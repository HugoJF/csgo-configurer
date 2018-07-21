<?php

namespace App\Forms;

use App\Installation;
use Kris\LaravelFormBuilder\Form;

class ServerForm extends Form
{
	public function buildForm()
	{
		$this->name();
		$this->ip();
		$this->port();
		$this->password();

		$this->ftp_host();
		$this->ftp_user();
		$this->ftp_password();
		$this->ftp_root();

		$this->installation_id();
	}

	private function name()
	{
		$this->add('name', 'text');
	}

	private function ip()
	{
		$this->add('ip', 'text');
	}

	private function port()
	{
		$this->add('port', 'text');
	}

	private function password()
	{
		$this->add('password', 'text');
	}

	private function ftp_host()
	{
		$this->add('ftp_host', 'text');
	}

	private function ftp_user()
	{
		$this->add('ftp_user', 'text');
	}

	private function ftp_password()
	{
		$this->add('ftp_password', 'text');
	}

	private function ftp_root()
	{
		$this->add('ftp_root', 'text');
	}

	private function installation_id()
	{
		$installations = Installation::all()->mapWithKeys(function ($installation) {
			return [$installation->id => $installation->name];
		});

		$this->add('installation_id', 'select', [
			'choices'     => $installations->toArray(),
			'selected'    => $this->getData('selected', 0),
			'empty_value' => '=== Installation ===',
		]);
	}
}
