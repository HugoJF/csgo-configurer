<?php

namespace App\Observers;

use App\FieldList;

class FieldListObserver
{
    /**
     * Handle the field list "deleted" event.
     *
     * @param  \App\FieldList  $fieldList
     * @return void
     */
    public function deleted(FieldList $fieldList)
    {
    	$fieldList->fieldLists()->delete();
    	$fieldList->fields()->delete();
    }
}
