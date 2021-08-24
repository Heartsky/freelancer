<?php

namespace App\Http\Controllers;

use App\Helper\Common;
use App\Models\Company;
use App\Models\Customer;
use App\Models\InvoiceImport;
use App\Models\InvoiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\DataImport;
use Illuminate\Support\Facades\Auth;


class SummaryController extends Controller
{


    public function addInvoice(Request $request) {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','customer_invoice'])) {
            return back(401);
        }


        $companies = Common::getCompany();

        return view('customer_invoice.insert_invoice',[
            'companies' => $companies,
        ]);

    }

    public function saveInvoice(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin', 'customer_invoice'])) {
            return back(401);
        }
        $customer =  Customer::find($request->input('customer_id'))->toArray();
        $param = [
            'item' => $request->input('item'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
            'customer_name' => $customer['name']
        ];
        InvoiceImport::create($param);
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
