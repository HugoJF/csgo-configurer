<?php

namespace App\Observers;

use App\List_;

class ListObserver
{
    /**
     * Handle the list_ "deleted" event.
     *
     * @param  \App\List_  $list
     * @return void
     */
    public function deleted(List_ $list)
    {
    	$list->constants()->delete();
    	$list->descendants()->delete();
    }
}
