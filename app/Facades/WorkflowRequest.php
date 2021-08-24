<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class WorkflowRequest extends Facade {


    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \App\Services\WorkflowRequest::class;

    }


}
