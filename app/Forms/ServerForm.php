<?php

namespace App\Forms;

use App\Installation;
use Kris\LaravelFormBuilder\Form;

class ServerForm extends Form
{
    public function buildForm()
    {
    	$installations = [];

    	foreach (Installation::all() as $installation) {
    		$installations[$installation->id] = $installation->name;
		}

        $this
            ->add('name', 'text')
            ->add('ip', 'text')
            ->add('port', 'text')
            ->add('password', 'text')
            ->add('ftp_host', 'text')
            ->add('ftp_user', 'text')
            ->add('ftp_password', 'text')
            ->add('ftp_root', 'text')
			->add('installation_id', 'select', [
				'choices' => $installations,
				'selected' => $this->getData('selected', 0),
				'empty_value' => '=== Installation ===',
			])
		;
    }
}
