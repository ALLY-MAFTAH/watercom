<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

use App\Helpers\ActivityLogHelper;
use App\Models\Customer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CustomerController extends Controller
{
    /**
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $customers = Customer::latest()->get();

        // foreach ($customers as $key => $value) {
        //     dd($value->goods);
        // }
        return view('customers.index', compact('customers'));
    }

    // SHOW CUSTOMER
    public function showCustomer(Request $request, Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    // POST CUSTOMER
    public function postCustomer(Request $request)
    {
        try {
            $attributes = $request->validate([
                'name' => 'required',
                'phone' => 'required',
            ]);

            $attributes['status'] = true;

            $customer =  self::addCustomer($attributes);

            // $customer = Customer::create($attributes);
            ActivityLogHelper::addToLog('Added customer ' . $customer->name);
        } catch (QueryException $th) {
            notify()->error($th->getMessage());
            return back();
        }
        notify()->success('You have successful added a customer');

        return Redirect::back();
    }
    public function addCustomer($attributes)
    {
        try {

            $customer = Customer::create($attributes);
            ActivityLogHelper::addToLog('Added customer ' . $customer->name);
            return $customer;
        } catch (QueryException $th) {
            notify()->error($th->getMessage());
            return back();
        }
    }

    // EDIT CUSTOMER
    public function putCustomer(Request $request, Customer $customer)
    {
        try {
            $attributes = $request->validate([
                'name' => 'required',
                'phone' => 'required',
            ]);

            $customer->update($attributes);
            ActivityLogHelper::addToLog('Updated customer ' . $customer->name);
        } catch (QueryException $th) {
            notify()->error($th->getMessage());
            return back();
        }
        notify()->success('You have successful edited customer');
        return redirect()->back();
    }

    // TOGGLE CUSTOMER STATUS
    public function toggleStatus(Request $request, Customer $customer)
    {

        $attributes = $request->validate([
            'is_special' => ['required', 'boolean'],
        ]);

        $customer->update($attributes);
        ActivityLogHelper::addToLog('Switched customer ' . $customer->name . ' status ');

        notify()->success('You have successfully updated customer status');
        return back();
    }

    // DELETE CUSTOMER
    public function deleteCustomer(Customer $customer)
    {

        $itsName = $customer->name;
        $customer->delete();
        ActivityLogHelper::addToLog('Deleted customer ' . $itsName);

        notify()->success('You have successful deleted ' . $itsName . '.');
        return back();
    }
}
