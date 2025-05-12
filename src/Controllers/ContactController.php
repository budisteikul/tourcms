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
use budisteikul\tourcms\Models\Contact;
use budisteikul\tourcms\Models\Message;
use budisteikul\tourcms\Helpers\WhatsappHelper;
use budisteikul\tourcms\Helpers\GeneralHelper;
use budisteikul\tourcms\Helpers\FirebaseHelper;

class ContactController extends Controller
{
    
    public function clear_messages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $id = $request->input('id');
        Message::where('contact_id',$id)->delete();
        
        $whatsapp = new WhatsappHelper;
        $whatsapp->messages($id);

        return response('OK', 200)->header('Content-Type', 'text/plain');
    }

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
        $var4 = null;
        $type = null;
        $template = null;

        switch($template_id)
        {

            case 101:
                $type = "reminder_step1";
                $template = "reminder_step1";
                $var1 = ucwords(strtolower($contact->name));
                $var2 = "The *Yogyakarta Night Walking and Food Tour* will start tonight at *6.30PM* and our meeting point is arround *Tugu Jogja* (Yogyakarta Monument)";
                $var3 = "By the way, do you have any food allergy or dietary restrictions?";
                $var4 = "https://maps.app.goo.gl/XYB5wbb5ckNNzfKv7";
            break;

            case 102:
                $type = "reminder_step1_alt";
                $template = "reminder_step1_alt";
                $image = config("site.assets")."/img/guide/kalika02.jpg";
                $var1 = ucwords(strtolower($contact->name));
                $var2 = "The *Yogyakarta Night Walking and Food Tour* will start tonight at *6.30PM* and our meeting point is arround *Tugu Jogja* (Yogyakarta Monument)";
                $var3 = "Her name is *Kalika*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var4 = "https://maps.app.goo.gl/XYB5wbb5ckNNzfKv7";
            break;

            case 103:
                $type = "reminder_step1_alt";
                $template = "reminder_step1_alt";
                $image = config("site.assets")."/img/guide/anisa01.jpeg";
                $var1 = ucwords(strtolower($contact->name));
                $var2 = "The *Yogyakarta Night Walking and Food Tour* will start tonight at *6.30PM* and our meeting point is arround *Tugu Jogja* (Yogyakarta Monument)";
                $var3 = "Her name is *Anisa*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var4 = "https://maps.app.goo.gl/XYB5wbb5ckNNzfKv7";
            break;

            case 121:
                $type = "text";
                $var1 = "Got it! Thank you for confirming 🙏😊";
            break;

            case 122:
                $type = "image";
                $image = config("site.assets")."/img/guide/kalika02.jpg";
                $var1 = "Her name is *Kalika*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
            break;

            case 123:
                $type = "image";
                $image = config("site.assets")."/img/guide/anisa01.jpeg";
                $var1 = "Her name is *Anisa*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
            break;


            case 201:
                $type = "reminder_step1";
                $template = "reminder_step1";
                $var1 = ucwords(strtolower($contact->name));
                $var2 = "The *Morning Food Tour in Yogyakarta* will start tomorrow morning at *8.00AM* and our meeting point is *Lupis Mbah Satinem*";
                $var3 = "By the way, do you have any food allergy or dietary restrictions?";
                $var4 = "https://maps.app.goo.gl/tn2biVoLgPTRrtQs8";
            break;

            case 202:
                $type = "reminder_step1_alt";
                $template = "reminder_step1_alt";
                $image = config("site.assets")."/img/guide/kalika02.jpg";
                $var1 = ucwords(strtolower($contact->name));
                $var2 = "The *Morning Food Tour in Yogyakarta* will start tomorrow morning at *8.00AM* and our meeting point is *Lupis Mbah Satinem*";
                $var3 = "Her name is *Kalika*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var4 = "https://maps.app.goo.gl/tn2biVoLgPTRrtQs8";
            break;

            case 203:
                $type = "reminder_step1_alt";
                $template = "reminder_step1_alt";
                $image = config("site.assets")."/img/guide/anisa01.jpeg";
                $var1 = ucwords(strtolower($contact->name));
                $var2 = "The *Morning Food Tour in Yogyakarta* will start tomorrow morning at *8.00AM* and our meeting point is *Lupis Mbah Satinem*";
                $var3 = "Her name is *Anisa*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var4 = "https://maps.app.goo.gl/tn2biVoLgPTRrtQs8";
            break;

            case 301:
                $type = "reminder_step1";
                $template = "reminder_step1";
                $var1 = ucwords(strtolower($contact->name));
                $var2 = "The *Bali Taman Anyar Village Tour* will start tomorrow morning at *9.30AM* and our meeting point is *SDN 3 Penarungan*";
                $var3 = "Please be there 15 minutes before the tour start 🙏";
                $var4 = "https://maps.app.goo.gl/1Qd734sCUGkY9ckX9";
            break;

            case 304:
                $type = "reminder_step1";
                $template = "reminder_step1";
                $var1 = ucwords(strtolower($contact->name));
                $var2 = "The *Bali Taman Anyar Village Tour* will start tomorrow morning at *9.30AM* and our meeting point is *SDN 2 Penarungan*";
                $var3 = "Please be there 15 minutes before the tour start 🙏";
                $var4 = "https://maps.app.goo.gl/6m9Bm1mrdLVZRefh6";
            break;
            
            

            case 303:
                $type = "reminder_step1_alt";
                $template = "reminder_step1_alt";
                $image = config("site.assets")."/img/guide/dharma01.jpeg";
                $var1 = ucwords(strtolower($contact->name));
                $var2 = "The *Bali Nighttime Walk and Food Tour in Denpasar* will start tomorrow evening at *5.00PM* and our meeting point is *Lapangan Puputan Badung*. Please wait near the sign *Plakat Nol Kilometer Kota Denpasar* 🙏";
                $var3 = "His name is *Dharma*. He will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var4 = "https://maps.app.goo.gl/oJHftuQAFRQGjZDv6";
            break;

            
            case 901:
                // Denpasar Night Food Tour
                $type = "request_review";
                $template = "request_review_241222";
                $var1 = ucwords(strtolower($contact->name));
                $var2 = "Denpasar";
                $var3 = "TripAdvisor";
                $var4 = "https://www.tripadvisor.com/UserReviewEdit-g297694-d27418484";
            break;

            case 902:
                // Taman Anyar
                $type = "request_review";
                $template = "request_review_241222";
                $var1 = ucwords(strtolower($contact->name));
                $var2 = "Taman Anyar";
                $var3 = "TripAdvisor";
                $var4 = "https://www.tripadvisor.com/UserReviewEdit-g1025508-d16807840";
            break;

            case 903:
                // Ubud Food Tour
                $type = "request_review";
                $template = "request_review_241222";
                $var1 = ucwords(strtolower($contact->name));
                $var2 = "Ubud";
                $var3 = "TripAdvisor";
                $var4 = "https://www.tripadvisor.com/UserReviewEdit-g297701-d27735579";
            break;
            
        }

        if($type=="request_review")
        {
            if($var1=="") $var1="friend";
            $components = [
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
                                            ],
                                            [
                                                "type"=>"text",
                                                "text"=> $var4
                                            ]
                                        ]
                                    ]
                            ];
            
            $whatsapp = new WhatsappHelper;
            $whatsapp->sendTemplate($contact->wa_id,$template, $components);
        }

        if($type=="reminder_step1")
        {
            if($var1=="") $var1="friend";
            $components = [
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
                                            ],
                                            [
                                                "type"=>"text",
                                                "text"=> $var4
                                            ]
                                        ]
                                    ]
                            ];
            
            $whatsapp = new WhatsappHelper;
            $whatsapp->sendTemplate($contact->wa_id,$template, $components);
        }

        if($type=="reminder_step1_alt")
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
                                            ],
                                            [
                                                "type"=>"text",
                                                "text"=> $var4
                                            ]
                                        ]
                                    ]
                              ];
            
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
