<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use budisteikul\toursdk\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\DataTables\PartnerDataTable;
use budisteikul\tourcms\DataTables\PartnerReportDataTable;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    
    public function index(PartnerDataTable $dataTable)
    {
        return $dataTable->render('tourcms::partner.index');
    }

    public function report(PartnerReportDataTable $dataTable)
    {
        return $dataTable->render('tourcms::partner.report');
    }

    public function show(Partner $partner)
    {
        $qrcode = QrCode::errorCorrection('H')->format('png')->margin(0)->size(630)->generate(''. env('APP_URL') .'/?ref='.$partner->tracking_code .'');
        $qrcode = 'data:image/png;base64, '. base64_encode($qrcode);
        list($type, $qrcode) = explode(';', $qrcode);
        list(, $qrcode)      = explode(',', $qrcode);
        $qrcode = base64_decode($qrcode);
        $path = Storage::disk('local')->put($partner->name .'.png', $qrcode);
        return response()->download(storage_path('app').'/'.$partner->name .'.png')->deleteFileAfterSend(true);
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
