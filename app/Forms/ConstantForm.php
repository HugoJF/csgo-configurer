<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ConstantForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('key', 'text')
			->add('value', 'text')
		;
    }
}
