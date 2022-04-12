<?php

namespace budisteikul\tourcms\Controllers;

use App\Http\Controllers\Controller;
use budisteikul\toursdk\Models\Vendor;
use budisteikul\tourcms\DataTables\VendorDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VendorDataTable $dataTable)
    {
        return $dataTable->render('tourcms::vendor.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tourcms::vendor.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:vendors,name',
        ]);
        
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $name =  $request->input('name');
        $contact_person =  $request->input('contact_person');
        $phone =  $request->input('phone');
        $email =  $request->input('email');
        $bank_code =  $request->input('bank_code');
        $account_number =  $request->input('account_number');

        $vendor = new Vendor();
        $vendor->name = $name;
        $vendor->contact_person = $contact_person;
        $vendor->phone = $phone;
        $vendor->email = $email;
        $vendor->bank_code = $bank_code;
        $vendor->account_number = $account_number;
        $vendor->save();

        return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        return view('tourcms::vendor.show',['vendor'=>$vendor]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        return view('tourcms::vendor.edit',['vendor'=>$vendor]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:vendors,name,'.$vendor->id,
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $name =  $request->input('name');
        $contact_person =  $request->input('contact_person');
        $phone =  $request->input('phone');
        $email =  $request->input('email');
        $bank_code =  $request->input('bank_code');
        $account_number =  $request->input('account_number');
        
        
        $vendor->name = $name;
        $vendor->contact_person = $contact_person;
        $vendor->phone = $phone;
        $vendor->email = $email;
        $vendor->bank_code = $bank_code;
        $vendor->account_number = $account_number;
        $vendor->save();

        return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
    }

}
