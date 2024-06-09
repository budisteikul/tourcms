<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\toursdk\Models\ShoppingcartCancellation;
use budisteikul\tourcms\DataTables\CancelsDataTable;
use budisteikul\toursdk\Helpers\PaymentHelper;

class CancelController extends Controller
{
    public function index(CancelsDataTable $dataTable)
    {
        return $dataTable->render('tourcms::cancels.index');
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        
    }

    public function show(ShoppingcartCancellation $cancel)
    {
        
    }

    public function edit(ShoppingcartCancellation $cancel)
    {
        
    }

    public function update(Request $request, ShoppingcartCancellation $cancel)
    {
        PaymentHelper::create_refund($cancel->shoppingcart);
        $cancel->refund = $cancel->amount;
        $cancel->status = 2;
        $cancel->save();
        
        return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
    }
   
    public function destroy(ShoppingcartCancellation $cancel)
    {
        
    }
}
