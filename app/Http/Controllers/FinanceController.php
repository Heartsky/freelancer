<?php

namespace App\Http\Controllers;

use App\Facades\WorkflowExport;
use App\Models\Company;
use App\Models\Customer;
use App\Models\InvoiceImport;
use App\Models\InvoiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\DataImport;
use Illuminate\Support\Facades\Auth;


class FinanceController extends Controller
{


    public function reportFinance(Request $request) {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin', 'finance_summary'])) {
            return back(401);
        }
        $companies = Company::all();

        return view('finance.report',[
            'companies' => $companies,
        ]);

    }

    public function exportFinance(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','finance_summary'])) {
            return back(401);
        }
        $company =  Company::find($request->input('company_id'));
        $param = [
            'cycle' => $request->input('cycle'),
            'rate-jpy' => $request->input('rate-jpy'),
            'rate-usd' => $request->input('rate-usd'),
            'company' => $company->toArray()
        ];
        $sevice = new \App\Services\WorkflowExport();
        $path = $sevice->exportFinance($param);
//        $path = WorkflowExport::exportFinance($param);

        $filename = "finance_report".$param['company']['code']. "_".date("ymd").'.xlsx';
        return response()->download($path, $filename, []);
        return back();
    }


    //
    public function upload(Request $request)
    {


    	$extensionAccept = ['xlsx', 'xls']; $message = [];
    	if($request->getMethod() == 'POST') {

    		$extenstion = $request->file('input-data')->extension();
    		if(in_array($extenstion, $extensionAccept)){

    			$file = $request->file('input-data');
		        $originalname = $file->getClientOriginalName();
		        $path = $file->storeAs('public', $originalname);

		        $inputFileName =storage_path("app/".$path);
    			// import data
    			$import = new DataImport();
                $message = $import->importData($inputFileName, $extenstion);

    		} else {
    			$message = [
	    			'status' => 'danger',
	    			'msg' => 'Only accept xlsx file!'
    			];
    		}
    	}

    	return view('upload.index',$message);
    }
}
