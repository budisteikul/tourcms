<?php
namespace budisteikul\tourcms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use budisteikul\tourcms\DataTables\ScheduleDataTable;
use budisteikul\toursdk\Models\ShoppingcartProduct;
use Illuminate\Support\Facades\Validator;
use budisteikul\toursdk\Helpers\CalendarHelper;

class ScheduleController extends Controller
{
	public function index(ScheduleDataTable $dataTable)
    {
        return $dataTable->render('tourcms::schedule.index');
    }

    public function edit($id)
    {
        $shoppingcart_product = ShoppingcartProduct::findOrFail($id);
        return view('tourcms::schedule.edit',['shoppingcart_product'=>$shoppingcart_product]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|string|date_format:Y-m-d H:i:s'
        ]);
        
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }


        $date =  $request->input('date');
        $shoppingcart_product = ShoppingcartProduct::findOrFail($id);

        CalendarHelper::update_calendar($shoppingcart_product->shoppingcart->confirmation_code);

        $shoppingcart_product->date = $date;
        $shoppingcart_product->save();

        CalendarHelper::create_calendar($shoppingcart_product->shoppingcart->confirmation_code);

        return response()->json([
                    "id" => "1",
                    "message" => $shoppingcart_product->shoppingcart->confirmation_code
                ]);

    }
}
?>