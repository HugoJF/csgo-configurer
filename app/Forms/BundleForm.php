<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class BundleForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('name', 'text')
			->add('slug', 'text')
			;
    }
}
