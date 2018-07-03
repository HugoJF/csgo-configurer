<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class FieldForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('name', 'text')
            ->add('description', 'text')
            ->add('key', 'text')
            ->add('default', 'text');
    }
}
