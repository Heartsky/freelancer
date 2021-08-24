<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Customer;
use App\Models\InvoiceImport;
use App\Models\InvoiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\DataImport;
use Illuminate\Support\Facades\Auth;


class InvoiceController extends Controller
{


    public function addInvoice(Request $request) {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','customer_invoice'])) {
            return back(401);
        }

        $roles = Auth::user()->roles();
        if($user->hasRole('admin')){
            $companies = Company::all();

        } else {
            $companies = [];
            foreach ($roles->get() as $role) {
                if($role->group != 'customer_invoice') {
                    continue;
                }
                $company = $role->company()->first();
                //  dd($company);
                $customers = $company->customers;
                foreach ($customers as $customer){
                    $listCustomer[$customer->id] = $customer;
                }

                $companies[$company->id] = $company;
            }
        }


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
            'customer_name' => $customer['name'],
            'company_id' => $request->input('company_id'),
            'customer_id' => $request->input('customer_id')
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
