<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use budisteikul\toursdk\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\DataTables\PartnerDataTable;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class PartnerController extends Controller
{
    
    public function index(PartnerDataTable $dataTable)
    {
        return $dataTable->render('tourcms::partner.index');
    }

    
    public function create()
    {
        return view('tourcms::partner.create');
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:partners,name',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $name =  $request->input('name');
        $tracking_code =  Uuid::uuid4()->toString();
        $description =  $request->input('description');

        $partner = new Partner();
        $partner->name = $name;
        $partner->description = $description;
        $partner->tracking_code = $tracking_code;
        $partner->save();

        return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
    }

    
    public function show(Partner $partner)
    {
        
    }

    
    public function edit(Partner $partner)
    {
        return view('tourcms::partner.edit',['partner'=>$partner]);
    }

    
    public function update(Request $request, Partner $partner)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:partners,name,'.$partner->id,
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $name =  $request->input('name');
        $description =  $request->input('description');

        $partner->name = $name;
        $partner->description = $description;
        $partner->save();

        return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
    }

   
    public function destroy(Partner $partner)
    {
		$partner->delete();
    }
}
