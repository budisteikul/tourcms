<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use budisteikul\coresdk\Models\FileTemp;
use budisteikul\toursdk\Models\Contact;
use budisteikul\toursdk\Models\Message;
use budisteikul\toursdk\Helpers\WhatsappHelper;
use budisteikul\toursdk\Helpers\GeneralHelper;
use budisteikul\toursdk\Helpers\FirebaseHelper;

class ContactController extends Controller
{
    
    public function template(Request $request)
    {
            $validator = Validator::make($request->all(), [
                'template_id' => 'required|string|max:255',
                'id' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors);
            }

            $id = $request->input('id');
            $template_id = $request->input('template_id');

            $contact = Contact::where('id',$id)->firstOrFail();

        $image = null;
        $var1 = null;
        $var2 = null;
        $var3 = null;
        $type = null;
        $template = null;
        switch($template_id)
        {
            case 1:
                $type = "template";
                $template = "booking_notification";
                $image = "https://storage.googleapis.com/storage.vertikaltrip.com/assets/img/whatsapp/kalika.jpeg";
                $var1 = "The Yogyakarta Night Walking and Food Tour will start tonight at *6.30PM* and our meeting point is arround *Tugu Jogja* (Yogyakarta Monument)";
                $var2 = "Her name is *Kalika*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var3 = "If you have any food allergy or dietary restrictions, tell us by reply this message 🙏😊";
            break;
            case 2:
                $type = "template";
                $template = "booking_notification";
                $image = "https://storage.googleapis.com/storage.vertikaltrip.com/assets/img/whatsapp/kalika.jpeg";
                $var1 = "The Yogyakarta Night Walking and Food Tour will start tonight at *6.30PM* and our meeting point is arround *Tugu Jogja* (Yogyakarta Monument)";
                $var2 = "Her name is *Kalika*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var3 = "See you 🙏😊";
            break;

            case 3:
                $type = "template";
                $template = "booking_notification";
                $image = "https://storage.googleapis.com/storage.vertikaltrip.com/assets/img/whatsapp/anisa.jpeg";
                $var1 = "The Yogyakarta Night Walking and Food Tour will start tonight at *6.30PM* and our meeting point is arround *Tugu Jogja* (Yogyakarta Monument)";
                $var2 = "Her name is *Anisa*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var3 = "See you 🙏😊";
            break;
            case 4:
                $type = "template";
                $template = "booking_notification";
                $image = "https://storage.googleapis.com/storage.vertikaltrip.com/assets/img/whatsapp/anisa.jpeg";
                $var1 = "The Yogyakarta Night Walking and Food Tour will start tonight at *6.45PM* and our meeting point is arround *Tugu Jogja* (Yogyakarta Monument)";
                $var2 = "Her name is *Anisa*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var3 = "See you 🙏😊";
            break;

            case 5:
                $type = "template";
                $template = "booking_notification";
                $image = "https://storage.googleapis.com/storage.vertikaltrip.com/assets/img/whatsapp/kalika.jpeg";
                $var1 = "The Morning Food Tour in Yogyakarta will start tomorrow at *7.30AM* and our meeting point is arround *Lupis Mbah Satinem*";
                $var2 = "Her name is *Kalika*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var3 = "If you have any food allergy or dietary restrictions, tell us by reply this message 🙏😊";
            break;
            case 6:
                $type = "template";
                $template = "booking_notification";
                $image = "https://storage.googleapis.com/storage.vertikaltrip.com/assets/img/whatsapp/kalika.jpeg";
                $var1 = "The Morning Food Tour in Yogyakarta will start tomorrow at *7.30AM* and our meeting point is arround *Lupis Mbah Satinem*";
                $var2 = "Her name is *Kalika*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var3 = "See you 🙏😊";
            break;

            case 7:
                $type = "template";
                $template = "booking_notification";
                $image = "https://storage.googleapis.com/storage.vertikaltrip.com/assets/img/whatsapp/anisa.jpeg";
                $var1 = "The Morning Food Tour in Yogyakarta will start tomorrow at *7.30AM* and our meeting point is arround *Lupis Mbah Satinem*";
                $var2 = "Her name is *Anisa*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var3 = "If you have any food allergy or dietary restrictions, tell us by reply this message 🙏😊";
            break;
            case 8:
                $type = "template";
                $template = "booking_notification";
                $image = "https://storage.googleapis.com/storage.vertikaltrip.com/assets/img/whatsapp/anisa.jpeg";
                $var1 = "The Morning Food Tour in Yogyakarta will start tomorrow at *7.30AM* and our meeting point is arround *Lupis Mbah Satinem*";
                $var2 = "Her name is *Anisa*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var3 = "See you 🙏😊";
            break;

            case 9:
                $type = "template";
                $template = "booking_notification";
                $image = "https://storage.googleapis.com/storage.vertikaltrip.com/assets/img/whatsapp/dea.jpeg";
                $var1 = "The Bali Night Walking and Food Tour will start tonight at *05.00PM* and our meeting point is in front of *Lapangan Puputan Badung* (Look for sign 0KM)";
                $var2 = "Her name is *Dea*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var3 = "See you 🙏😊";
            break;

            case 11:
                $type = "text";
                $var1 = "By the way, do you have any food allergy or dietary requirements?";
            break;

            case 12:
                $type = "text";
                $var1 = "Got it 🫡 Thank you for your confirmation 🙏😊";
            break;

            case 13:
                $type = "image";
                $var1 = "Got it 🫡 Thank you for your confirmation 🙏😊";
                $image = "https://storage.googleapis.com/storage.vertikaltrip.com/assets/img/whatsapp/kalika.jpeg";
                $var1 = "Her name is *Kalika*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
            break;

            case 14:
                $type = "image";
                $var1 = "Got it 🫡 Thank you for your confirmation 🙏😊";
                $image = "https://storage.googleapis.com/storage.vertikaltrip.com/assets/img/whatsapp/anisa.jpeg";
                $var1 = "Her name is *Anisa*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
            break;

            case 15:
                $type = "image";
                $image = "https://storage.googleapis.com/storage.vertikaltrip.com/assets/img/whatsapp/dea.jpeg";
                $var1 = "Her name is *Dea*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
            break;

            case 21:
                $type = "template";
                $template = "booking_reminder";
                $var1 = "The Yogyakarta Night Walking and Food Tour will start tonight at *6.30PM* and our meeting point is arround *Tugu Jogja* (Yogyakarta Monument)";
            break;
            case 22:
                $type = "template";
                $template = "booking_reminder";
                $var1 = "The Morning Food Tour in Yogyakarta will start tomorrow at *7.30AM* and our meeting point is arround *Lupis Mbah Satinem*";
            break;
            case 23:
                $type = "template";
                $template = "booking_reminder";
                $var1 = "The Bali Night Walking and Food Tour will start tonight at *05.00PM* and our meeting point is in front of *Lapangan Puputan Badung* (Look for sign 0KM)";
            break;
        }

        if($type=="template")
        {
            if($template=="booking_notification")
            {
                $components = [
                                    [
                                        "type"=> "header",
                                        "parameters" => [
                                            [
                                                "type"=> "image",
                                                "image" => [
                                                    "link" => $image
                                                ]
                                            ]
                                        ]
                                    ],
                                    [
                                        "type"=> "body",
                                        "parameters" => [
                                            [
                                                "type"=>"text",
                                                "text"=> $var1
                                            ],
                                            [
                                                "type"=>"text",
                                                "text"=> $var2
                                            ],
                                            [
                                                "type"=>"text",
                                                "text"=> $var3
                                            ]
                                        ]
                                    ]
                              ];

            }

            if($template=="booking_reminder")
            {
                $components = [
                                    [
                                        "type"=> "body",
                                        "parameters" => [
                                            [
                                                "type"=>"text",
                                                "text"=> $var1
                                            ]
                                        ]
                                    ]
                              ];

            }
            
            $whatsapp = new WhatsappHelper;
            $whatsapp->sendTemplate($contact->wa_id,$template, $components);
        }

        if($type=="text")
        {
            $whatsapp = new WhatsappHelper;
            $whatsapp->sendText($contact->wa_id,$var1);
        }

        if($type=="image")
        {
            $whatsapp = new WhatsappHelper;
            $whatsapp->sendImage($contact->wa_id,$image,$var1);
        }
        

        return response('OK', 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreContactRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreContactRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        $whatsapp = new WhatsappHelper;
        $whatsapp->messages($contact->id);
        return view('tourcms::contact.edit',['contact'=>$contact,'file_key'=>Uuid::uuid4()->toString()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateContactRequest  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        $key = $request->input('key');
        $filetemps = FileTemp::where('key',$key)->get();
       
        

        if($filetemps->count()==0)
        {
            $validator = Validator::make($request->all(), [
                'message_text' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors);
            }
        }
        

        $message_text =  $request->input('message_text');

        $whatsapp = new WhatsappHelper;
        if($filetemps->count()==0)
        {
            $whatsapp->sendText($contact->wa_id,$message_text);
        }
        else
        {
            $caption = '';
            if($message_text!="") $caption = $message_text;

            $image_id = Uuid::uuid4()->toString() .'.jpg';
            foreach($filetemps as $filetemp)
            { 
                $contents = file_get_contents(storage_path('app').'/'. $filetemp->file);
                Storage::disk('gcs')->put('images/whatsapp/'. $image_id, $contents);
                $filetemp->delete();
            }
            $whatsapp->sendImage($contact->wa_id,config('site.image').'/whatsapp/'. $image_id,$caption);
        }

        

        

        return response()->json([
                    "id" => "1",
                    "message" => $filetemps->count()
                ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        //
    }
}
