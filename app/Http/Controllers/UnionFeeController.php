<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaborUnionFee;

class UnionFeeController extends Controller
{
    //
    public function index(Request $request)
    {

    	$data = LaborUnionFee::paginate(15);

    	return view('union_fee.list_input', compact('data'));
    }
}
