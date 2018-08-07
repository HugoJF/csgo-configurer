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
		$this->add('name', 'text', [
			'label'      => 'Server Name',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'Server name',
			],
		]);
	}

	private function ip()
	{
		$this->add('ip', 'text', [
			'label'      => 'Server IP',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'Server IP',
			],
		]);
	}

	private function port()
	{
		$this->add('port', 'text', [
			'label'      => 'Server Port',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'Server Port',
			],
		]);
	}

	private function password()
	{
		$this->add('password', 'text', [
			'label'      => 'Server Password (RCON)',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'Server Password (RCON)',
			],
		]);
	}

	private function ftp_host()
	{
		$this->add('ftp_host', 'text', [
			'label'      => 'Server FTP Host',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'Server FTP Host',
			],
		]);
	}

	private function ftp_user()
	{
		$this->add('ftp_user', 'text', [
			'label'      => 'Server FTP User',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'Server FTP User',
			],
		]);
	}

	private function ftp_password()
	{
		$this->add('ftp_password', 'text', [
			'label'      => 'Server FTP Password',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'Server FTP Password',
			],
		]);
	}

	private function ftp_root()
	{
		$this->add('ftp_root', 'text', [
			'label'      => 'Server FTP Root',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'Server FTP Root',
			],
		]);
	}

	private function installation_id()
	{
		$installations = Installation::all()->mapWithKeys(function ($installation) {
			return [$installation->id => $installation->name];
		});

		$this->add('installation_id', 'select', [
			'label'       => 'Server Selected Installations',
			'choices'     => $installations->toArray(),
			'selected'    => $this->getData('selected', 0),
			'empty_value' => '=== Installation ===',
			'help_block'  => [
				'text' => 'The installation used in this server.',
			],
		]);
	}
}
