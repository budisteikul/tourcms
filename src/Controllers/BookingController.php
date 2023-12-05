<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use budisteikul\toursdk\Models\Shoppingcart;
use budisteikul\toursdk\Helpers\BokunHelper;
use budisteikul\toursdk\Helpers\PaypalHelper;
use budisteikul\toursdk\Helpers\BookingHelper;
use budisteikul\toursdk\Helpers\PaymentHelper;
use budisteikul\toursdk\Models\Channel;
use budisteikul\toursdk\Models\Product;

use budisteikul\tourcms\DataTables\BookingDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use budisteikul\toursdk\Mail\BookingConfirmedMail;

use budisteikul\toursdk\Helpers\FirebaseHelper;
use budisteikul\toursdk\Models\Review;
use budisteikul\toursdk\Models\ShoppingcartProduct;
use budisteikul\toursdk\Models\ShoppingcartQuestion;
use budisteikul\toursdk\Models\ShoppingcartQuestionOption;
use budisteikul\toursdk\Models\ShoppingcartPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;



class BookingController extends Controller
{
    public function __construct()
    {
        $this->bookingChannelUUID = env("BOKUN_BOOKING_CHANNEL");
        $this->currency = env("BOKUN_CURRENCY");
        $this->lang = env("BOKUN_LANG");

    }

    public function shoppingcart_session()
    {
        if(!Session::has('sessionId')){
            $sessionId = Uuid::uuid4()->toString();
            Session::put('sessionId',$sessionId);
        }
        return Session::get('sessionId');
    }

    public function checkout(Request $request)
    {

        $sessionId = self::shoppingcart_session();
        
        $shoppingcart = Cache::get('_'. $sessionId, 'empty');

        //print_r($shoppingcart);

        if($shoppingcart=="empty")
        {
            return redirect(route('route_tourcms_booking.index'));
        }
        
        if($shoppingcart->products==NULL)
        {
            Cache::forget('_'. $sessionId);
            return redirect(route('route_tourcms_booking.index'));
        }

        $channels = Channel::get();
        return view('tourcms::booking.checkout')
                ->with([
                        'shoppingcart'=>$shoppingcart,
                        'channels'=>$channels
                    ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BookingDataTable $dataTable)
    {
        return $dataTable->render('tourcms::booking.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('tourcms::booking.create',['products'=>$products]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            
            $data = json_decode($request->getContent(), true);

            $validator = Validator::make(json_decode($request->getContent(), true), [
                    'sessionId' => ['required', 'string', 'max:255'],
                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors();
                    return response()->json($errors);
                }

            $sessionId = $data['sessionId'];

            $shoppingcart = Cache::get('_'.$sessionId);

            $shoppingcart->booking_channel = $data['bookingChannel'];
            
            Cache::forget('_'.$sessionId);
            Cache::add('_'.$sessionId, $shoppingcart, 172800);

            $shoppingcart = BookingHelper::save_question_json($sessionId,$data);

            BookingHelper::set_confirmationCode($sessionId);

            if($data['payment_type']!="none")
            {
                BookingHelper::set_bookingStatus($sessionId,'PENDING');
                $payment_type_arr = explode("-", $data['payment_type']);
                $shoppingcart= PaymentHelper::create_payment($sessionId,$payment_type_arr[0],$payment_type_arr[1]);
            }
            else
            {
                BookingHelper::set_bookingStatus($sessionId,'CONFIRMED');
                $shoppingcart= PaymentHelper::create_payment($sessionId,"none");
            }
            



            
            $shoppingcart = BookingHelper::confirm_booking($sessionId,false);


            
            //Fee ========================================================================
                $fee = $data['fee'];
                if($fee=="") $fee = 0;

                $shoppingcart->fee = $fee;
                $shoppingcart->total = $shoppingcart->subtotal - $shoppingcart->discount - $fee;
                $shoppingcart->due_now = $shoppingcart->subtotal - $shoppingcart->discount - $fee;
                $shoppingcart->save();

                //Fee per product
                $fee_product = $fee / $shoppingcart->shoppingcart_products->count();
                foreach($shoppingcart->shoppingcart_products as $shoppingcart_product)
                {
                    $shoppingcart_product->fee = $fee_product;
                    $shoppingcart_product->total = $shoppingcart_product->subtotal - $shoppingcart_product->discount - $shoppingcart_product->fee;
                    $shoppingcart_product->due_now = $shoppingcart_product->subtotal - $shoppingcart_product->discount - $shoppingcart_product->fee;
                    $shoppingcart_product->save();

                    //Fee per product detail
                    $fee_product_detail = $fee_product / $shoppingcart_product->shoppingcart_product_details->count();
                    foreach($shoppingcart_product->shoppingcart_product_details as $shoppingcart_product_detail)
                    {
                        $shoppingcart_product_detail->fee = $fee_product;
                        $shoppingcart_product_detail->total = $shoppingcart_product_detail->subtotal - $shoppingcart_product_detail->discount - $shoppingcart_product_detail->fee;
                        $shoppingcart_product_detail->save();
                    }
                }
            
            
            
            return response()->json([
                    "message" => 'success'
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shoppingcart = Shoppingcart::where('id',$id)->firstOrFail();
        //BookingHelper::booking_expired($shoppingcart);
        return view('tourcms::booking.show')->with(['shoppingcart'=>$shoppingcart]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sessionId = self::shoppingcart_session();
        $contents = BokunHelper::get_product($id);

        $bookingChannelUUID = $this->bookingChannelUUID;
        $currency = $this->currency;
        $lang = $this->lang;
        
        $pickup = '';
        if($contents->meetingType=='PICK_UP' || $contents->meetingType=='MEET_ON_LOCATION_OR_PICK_UP')
        {
            $pickup = BokunHelper::get_product_pickup($id);
        }

        $calendar = BokunHelper::get_calendar_new($id);
        $firstavailability = BookingHelper::get_firstAvailability($id,$calendar->year,$calendar->month);
        
        $microtime = $firstavailability[0]['date'];
        $month = date("n",$microtime/1000);
        $year = date("Y",$microtime/1000);
        $embedded = "false";

        return view('tourcms::booking.calendar')->with(['currency'=>$currency,'lang'=>$lang,'embedded'=>$embedded,'contents'=>$contents,'pickup'=>$pickup,'sessionId'=>$sessionId,'bookingChannelUUID'=>$bookingChannelUUID,'firstavailability'=>$firstavailability,'year'=>$year,'month'=>$month]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->input('update')!="")
        {
            $validator = Validator::make($request->all(), [
                    'update' => 'in:capture,void'
            ]);
                
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors);
            }

            $shoppingcart = Shoppingcart::findOrFail($id);
            $update = $request->input('update');
            if($update=="capture")
            {
                $captureId = PaypalHelper::captureAuth($shoppingcart->shoppingcart_payment->authorization_id);
                $shoppingcart->shoppingcart_payment->authorization_id = $captureId;
                $shoppingcart->shoppingcart_payment->save();
                PaymentHelper::confirm_payment($shoppingcart,"CONFIRMED",true);
            }
            if($update=="void")
            {
                PaypalHelper::voidPaypal($shoppingcart->shoppingcart_payment->authorization_id);
                PaymentHelper::confirm_payment($shoppingcart,"CANCELED",true);
            }
            
            return response()->json([
                        "id"=>"1",
                        "message"=>'success'
                    ]);
        }

        if($request->input('action')=="cancel")
        {
            $shoppingcart = Shoppingcart::findOrFail($id);
            PaymentHelper::confirm_payment($shoppingcart,"CANCELED");
            PaymentHelper::create_refund($shoppingcart);
            return response()->json([
                        "id"=>"1",
                        "message"=>'success'
                    ]);
        }

        if($request->input('action')=="confirm")
        {
            $shoppingcart = Shoppingcart::findOrFail($id);
            PaymentHelper::confirm_payment($shoppingcart,"CONFIRMED");

            return response()->json([
                        "id"=>"1",
                        "message"=>'success'
                    ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shoppingcart = Shoppingcart::findOrFail($id);
        BookingHelper::delete_shoppingcart($shoppingcart);
    }
}
