<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use budisteikul\toursdk\Models\Shoppingcart;
use budisteikul\toursdk\Helpers\BokunHelper;
use budisteikul\toursdk\Helpers\PaypalHelper;
use budisteikul\toursdk\Helpers\BookingHelper;
use budisteikul\toursdk\Models\Channel;
use budisteikul\toursdk\Models\Product;

use budisteikul\tourcms\DataTables\BookingDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use budisteikul\toursdk\Mail\BookingConfirmedMail;


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

            if($data['payment_type']!="none")
            {
                $validator = Validator::make(json_decode($request->getContent(), true), [
                    'sessionId' => ['required', 'string', 'max:255'],
                    'questions.firstName' => ['required', 'string'],
                    'questions.lastName' => ['required', 'string'],
                    'questions.phoneNumber' => ['required', 'string'],
                    'questions.email' => ['required', 'string'],
                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors();
                    return response()->json($errors);
                }
            }
            else
            {
                $validator = Validator::make(json_decode($request->getContent(), true), [
                    'sessionId' => ['required', 'string', 'max:255'],
                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors();
                    return response()->json($errors);
                }
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
                $payment_type_arr = explode("|", $data['payment_type']);
               
                $shoppingcart= BookingHelper::create_payment($sessionId,$payment_type_arr[0],$payment_type_arr[1]);
            }
            else
            {
                BookingHelper::set_bookingStatus($sessionId,'CONFIRMED');
                $shoppingcart= BookingHelper::create_payment($sessionId,"none");
            }
            
            $shoppingcart = BookingHelper::confirm_booking($sessionId,false);
            
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
                PaypalHelper::captureAuth($shoppingcart->shoppingcart_payment->authorization_id);
                $shoppingcart->booking_status = 'CONFIRMED';
                $shoppingcart->save();
                $shoppingcart->shoppingcart_payment->payment_status = 2;
                $shoppingcart->shoppingcart_payment->save();
            }
            if($update=="void")
            {
                PaypalHelper::voidPaypal($shoppingcart->shoppingcart_payment->authorization_id);
                $shoppingcart->booking_status = 'CANCELED';
                $shoppingcart->save();
                $shoppingcart->shoppingcart_payment->payment_status = 3;
                $shoppingcart->shoppingcart_payment->save();
            }
            
            return response()->json([
                        "id"=>"1",
                        "message"=>'success'
                    ]);
        }

        if($request->input('action')=="cancel")
        {
            $shoppingcart = Shoppingcart::findOrFail($id);

            BookingHelper::change_booking_status($shoppingcart,"CANCELED");

            return response()->json([
                        "id"=>"1",
                        "message"=>'success'
                    ]);
        }

        if($request->input('action')=="confirm")
        {
            $shoppingcart = Shoppingcart::findOrFail($id);

            BookingHelper::change_booking_status($shoppingcart,"CONFIRMED");

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
        BookingHelper::change_booking_status($shoppingcart,"DELETED");
    }
}
