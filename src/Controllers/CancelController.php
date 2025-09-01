<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\Models\ShoppingcartCancellation;
use budisteikul\tourcms\Models\ShoppingcartProduct;
use budisteikul\tourcms\DataTables\CancelsDataTable;
use budisteikul\tourcms\Helpers\PaymentHelper;
use budisteikul\tourcms\Helpers\GeneralHelper;

class CancelController extends Controller
{
   

    public function index(CancelsDataTable $dataTable)
    {
        $stripe_amount = 0 ;
        $model = ShoppingcartProduct::with(['shoppingcart' => function ($query) {
                    $query = $query->with(['shoppingcart_payment' => function ($query) {
                        return $query->where('payment_provider','stripe');
                    }]);
                 }])
                 ->whereHas('shoppingcart', function ($query) {
                    return $query->where('booking_status','CONFIRMED');
                 })->whereDate('date', '>=', date('Y-m-01'))->whereNotNull('date')->orderBy('date')->orderBy('id')->get();


        foreach($model as $x)
        {
            if(isset($x->shoppingcart->shoppingcart_payment->payment_provider))
            {
                $stripe_amount += $x->shoppingcart->shoppingcart_payment->amount;
            }
        }

        $paypal_amount = 0 ;
        $model = ShoppingcartProduct::with(['shoppingcart' => function ($query) {
                    $query = $query->with(['shoppingcart_payment' => function ($query) {
                        return $query->where('payment_provider','paypal');
                    }]);
                 }])
                 ->whereHas('shoppingcart', function ($query) {
                    return $query->where('booking_status','CONFIRMED');
                 })->whereDate('date', '>=', date('Y-m-01'))->whereNotNull('date')->orderBy('date')->orderBy('id')->get();

        foreach($model as $x)
        {
            if(isset($x->shoppingcart->shoppingcart_payment->payment_provider))
            {
                $paypal_amount += $x->shoppingcart->shoppingcart_payment->amount;
            }
        }

        $wise_amount = 0 ;
        $model = ShoppingcartProduct::with(['shoppingcart' => function ($query) {
                    $query = $query->with(['shoppingcart_payment' => function ($query) {
                        return $query->where('payment_provider','wise');
                    }]);
                 }])
                 ->whereHas('shoppingcart', function ($query) {
                    return $query->where('booking_status','CONFIRMED');
                 })->whereDate('date', '>=', date('Y-m-01'))->whereNotNull('date')->orderBy('date')->orderBy('id')->get();

        foreach($model as $x)
        {
            if(isset($x->shoppingcart->shoppingcart_payment->payment_provider))
            {
                $wise_amount += $x->shoppingcart->shoppingcart_payment->amount;
            }
        }

        $stripe_amount = GeneralHelper::numberFormat($stripe_amount,'USD').' USD';
        $paypal_amount = GeneralHelper::numberFormat($paypal_amount,'USD').' USD';
        $wise_amount = GeneralHelper::numberFormat($wise_amount,'IDR').' IDR';
        return $dataTable->render('tourcms::cancels.index',['stripe_amount'=>$stripe_amount,'paypal_amount'=>$paypal_amount,'wise_amount'=>$wise_amount]);
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
