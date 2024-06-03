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
use budisteikul\toursdk\Helpers\SettingHelper;
use budisteikul\toursdk\Models\Review;
use budisteikul\toursdk\Models\ShoppingcartProduct;
use budisteikul\toursdk\Models\ShoppingcartQuestion;
use budisteikul\toursdk\Models\ShoppingcartQuestionOption;
use budisteikul\toursdk\Models\ShoppingcartPayment;
use budisteikul\toursdk\Models\ShoppingcartCancellation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;



class BookingController extends Controller
{
    public function __construct()
    {
        $this->bookingChannelUUID = env("BOKUN_BOOKING_CHANNEL");
        $this->currency = SettingHelper::getSetting('currency');
        $this->lang = env("BOKUN_LANG");
    }

    public function question_edit($id)
    {
        $shoppingcart = Shoppingcart::where('id',$id)->firstOrFail();
        $mainContactDetails = ShoppingcartQuestion::where('shoppingcart_id',$shoppingcart->id)->where('type','mainContactDetails')->orderBy('order')->get();
        $activityBookings = ShoppingcartQuestion::where('shoppingcart_id',$shoppingcart->id)->where('type','activityBookings')->orderBy('booking_id')->orderBy('order')->get();
        return view('tourcms::booking.question_edit',[
            'id'=>$shoppingcart->id,
            'mainContactDetails'=>$mainContactDetails,
            'activityBookings'=>$activityBookings,
        ]);
    }

    

    public function question_update($id,Request $request)
    {

        $array = $request->post();
        foreach ($array as $key => $value)
        {
            
            $question = ShoppingcartQuestion::where('shoppingcart_id',$id)->where('question_id',$key)->first();
            if($question)
            {
                $question->answer = $value;
                $question->save();

                if($question->data_type=="OPTIONS")
                {
                    $options = ShoppingcartQuestionOption::where('shoppingcart_question_id',$question->id)->get();
                    foreach($options as $option)
                    {
                        $answer = 0;
                        if($option->id==$value) $answer = 1;
                        $option->answer = $answer;
                        $option->save();
                    }
                    
                }
            }
            
            
        }
        

        return response()->json([
                    "id" => "1",
                    "message" => ''
                ]);
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
            
            if($data['payment']!="none")
            {
                $payment = ['CREDIT_CARD'];
                if($data['payment']=="qris") $payment = ['QRIS'];
                if($data['payment']=="virtual_account") $payment = ['BNI','BSI','BRI','MANDIRI','PERMATA','SAHABAT_SAMPOERNA'];
                if($data['payment']=="ewallet") $payment = ['OVO','DANA'];
                if($data['payment']=="paylater") $payment = ['AKULAKU','UANGME'];
                BookingHelper::set_bookingStatus($sessionId,'PENDING');
                $shoppingcart= PaymentHelper::create_payment($sessionId,"xendit","invoice",$payment);
            }
            else
            {
                BookingHelper::set_bookingStatus($sessionId,'CONFIRMED');
                $shoppingcart= PaymentHelper::create_payment($sessionId,"none");
                // set to paid
                $shoppingcart = PaymentHelper::set_paymentStatus($sessionId,2);
            }

            
            $shoppingcart = BookingHelper::confirm_booking($sessionId,false);
            
            //remove cancellation policy
            if($shoppingcart->booking_channel!="WEBSITE")
            {
                foreach($shoppingcart->shoppingcart_products as $shoppingcart_product)
                {
                    $shoppingcart_product->cancellation = "Referring to ". $shoppingcart->booking_channel ." policy";
                    $shoppingcart_product->save();
                }
            }

            if($data['confirmation_code']!="")
            {
                $shoppingcart->confirmation_code = $data['confirmation_code'];
                $shoppingcart->save();
            }

            if($data['wa_notif']=="yes")
            {
                BookingHelper::shoppingcart_whatsapp($shoppingcart);
            }
            

            //Fee ========================================================================
            /*
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
            */
            
            
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
        $contact = BookingHelper::get_answer_contact($shoppingcart);
        //BookingHelper::booking_expired($shoppingcart);
        return view('tourcms::booking.show')->with(['shoppingcart'=>$shoppingcart,'contact'=>$contact]);
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

        if($request->input('action')=="cancel")
        {
            $shoppingcart = Shoppingcart::findOrFail($id);
            
            $cancel = New ShoppingcartCancellation();
            $cancel->shoppingcart_id = $shoppingcart->id;
            $cancel->currency = $shoppingcart->shoppingcart_payment->currency;
            $cancel->amount = $shoppingcart->shoppingcart_payment->amount;
            if($shoppingcart->booking_channel!="WEBSITE")
            {
                $cancel->refund = $shoppingcart->shoppingcart_payment->amount;
            }
            else
            {
                if($shoppingcart->shoppingcart_payment->payment_status==2)
                {
                    $cancel->refund = 0;
                }
                else
                {
                    $cancel->refund = $shoppingcart->shoppingcart_payment->amount;
                }
                
            }
            
            $cancel->save();

            PaymentHelper::confirm_payment($shoppingcart,"CANCELED");

            return response()->json([
                        "id"=>"1",
                        "message"=>'success'
                    ]);
        }

        if($request->input('action')=="paid")
        {
            $shoppingcart = Shoppingcart::findOrFail($id);
            $shoppingcart->shoppingcart_payment->payment_status = 2;
            $shoppingcart->shoppingcart_payment->save();
            return response()->json([
                        "id"=>"1",
                        "message"=>'success'
                    ]);
        }

        if($request->input('action')=="resend_whatsapp")
        {
            $shoppingcart = Shoppingcart::findOrFail($id);
            BookingHelper::shoppingcart_whatsapp($shoppingcart);
            return response()->json([
                        "id"=>"1",
                        "message"=>'success'
                    ]);
        }

        if($request->input('action')=="resend_email")
        {
            $shoppingcart = Shoppingcart::findOrFail($id);
            BookingHelper::shoppingcart_mail($shoppingcart);
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
