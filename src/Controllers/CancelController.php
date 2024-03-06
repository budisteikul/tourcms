<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\toursdk\Models\ShoppingcartCancellation;
use budisteikul\tourcms\DataTables\CancelsDataTable;

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
        
    }
   
    public function destroy(ShoppingcartCancellation $cancel)
    {
        
    }
}
