<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\DataImport;

class UploadController extends Controller
{

    //
    public function upload(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','import_resource'])) {
            return back(401);
        }

        $roles = Auth::user()->roles();
        if($user->hasRole('admin')){
            $companies = Company::all();

        } else {
            $companies = [];

            foreach ($roles->get() as $role) {
                if($role->group != 'import_resource') {
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


    	$extensionAccept = ['xlsx', 'xls']; $message = [];
    	if($request->getMethod() == 'POST') {

    		$extenstion = $request->file('input-data')->extension();
    		if(in_array($extenstion, $extensionAccept)){

    			$file = $request->file('input-data');
		        $originalname = $file->getClientOriginalName();
		        $path = $file->storeAs('public', $originalname);

		        $inputFileName =storage_path("app/".$path);
		        $company = $request->input('company_id');
                $team = $request->input('team_id');
    			// import data
    			$import = new DataImport();
                $message = $import->importData($inputFileName, ['company' => $company,'team' => $team]);

    		} else {
    			$message = [
	    			'status' => 'danger',
	    			'msg' => 'Only accept xlsx file!'
    			];
    		}
    	}
        $message['companies'] = $companies;

    	return view('upload.index',$message);
    }
}
