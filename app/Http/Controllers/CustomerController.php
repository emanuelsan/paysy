<?php

namespace App\Http\Controllers;

use App\Customer;

use App\Http\Requests;

use Illuminate\Http\Request;

use Hashids;

class CustomerController extends Controller
{
    public function welcome(Request $request)
    {
        if (!$request->input('customer')) {
            return view('welcome');
        } else {
            $customer = Customer::findOrFail(Hashids::decode($request->input('customer')));
            if (!$customer->isEmpty())
                return $customer;
            abort(404);
        }
    }
}
