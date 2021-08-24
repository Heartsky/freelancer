<?php

namespace App\Http\Controllers\Backend;

use App\Facades\Workflow;
use App\Facades\WorkflowExport;
use App\Http\Controllers\Controller;

class ExportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        WorkflowExport::exportSample();
    }
}
