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
        $var4 = null;
        $type = null;
        $template = null;

        switch($template_id)
        {
            case 1:
                $type = "template";
                $template = config('site.wa_reminder');
                $image = config("site.assets")."/img/guide/kalika01.jpeg";
                $var1 = $contact->name;
                $var2 = "The Yogyakarta Night Walking and Food Tour will start tonight at *6.30PM* and our meeting point is arround *Tugu Jogja* (Yogyakarta Monument)";
                $var3 = "Her name is *Kalika*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var4 = "If you have any food allergy or dietary restrictions, tell us by reply this message 🙏😊";
            break;

            case 2:
                $type = "template";
                $template = config('site.wa_reminder');
                $image = config("site.assets")."/img/guide/kalika01.jpeg";
                $var1 = $contact->name;
                $var2 = "The Yogyakarta Night Walking and Food Tour will start tonight at *6.30PM* and our meeting point is arround *Tugu Jogja* (Yogyakarta Monument)";
                $var3 = "Her name is *Kalika*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var4 = "See you 🙏😊";
            break;

            case 3:
                $type = "template";
                $template = config('site.wa_reminder');
                $image = config("site.assets")."/img/guide/anisa01.jpeg";
                $var1 = $contact->name;
                $var2 = "The Yogyakarta Night Walking and Food Tour will start tonight at *6.30PM* and our meeting point is arround *Tugu Jogja* (Yogyakarta Monument)";
                $var3 = "Her name is *Anisa*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var4 = "If you have any food allergy or dietary restrictions, tell us by reply this message 🙏😊";
            break;

            case 4:
                $type = "template";
                $template = config('site.wa_reminder');
                $image = config("site.assets")."/img/guide/anisa01.jpeg";
                $var1 = $contact->name;
                $var2 = "The Yogyakarta Night Walking and Food Tour will start tonight at *6.30PM* and our meeting point is arround *Tugu Jogja* (Yogyakarta Monument)";
                $var3 = "Her name is *Anisa*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var4 = "See you 🙏😊";
            break;

            case 5:
                $type = "template";
                $template = config('site.wa_reminder');
                $image = config("site.assets")."/img/guide/kalika01.jpeg";
                $var1 = $contact->name;
                $var2 = "The Morning Food Tour in Yogyakarta will start tomorrow at *7.30AM* and our meeting point is arround *Lupis Mbah Satinem*";
                $var3 = "Her name is *Kalika*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var4 = "If you have any food allergy or dietary restrictions, tell us by reply this message 🙏😊";
            break;
            case 6:
                $type = "template";
                $template = config('site.wa_reminder');
                $image = config("site.assets")."/img/guide/kalika01.jpeg";
                $var1 = $contact->name;
                $var2 = "The Morning Food Tour in Yogyakarta will start tomorrow at *7.30AM* and our meeting point is arround *Lupis Mbah Satinem*";
                $var3 = "Her name is *Kalika*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var4 = "See you 🙏😊";
            break;

            case 7:
                $type = "template";
                $template = config('site.wa_reminder');
                $image = config("site.assets")."/img/guide/anisa01.jpeg";
                $var1 = $contact->name;
                $var2 = "The Morning Food Tour in Yogyakarta will start tomorrow at *7.30AM* and our meeting point is arround *Lupis Mbah Satinem*";
                $var3 = "Her name is *Anisa*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var4 = "If you have any food allergy or dietary restrictions, tell us by reply this message 🙏😊";
            break;
            
            case 8:
                $type = "template";
                $template = config('site.wa_reminder');
                $image = config("site.assets")."/img/guide/anisa01.jpeg";
                $var1 = $contact->name;
                $var2 = "The Morning Food Tour in Yogyakarta will start tomorrow at *7.30AM* and our meeting point is arround *Lupis Mbah Satinem*";
                $var3 = "Her name is *Anisa*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var4 = "See you 🙏😊";
            break;

            case 9:
                $type = "template";
                $template = config('site.wa_reminder');
                $image = config("site.assets")."/img/guide/dea01.jpeg";
                $var1 = $contact->name;
                $var2 = "The Bali Night Walking and Food Tour will start tonight at *05.00PM* and our meeting point is in front of *Lapangan Puputan Badung* (Look for sign 0KM)";
                $var3 = "Her name is *Dea*. She will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var4 = "See you 🙏😊";
            break;

            case 10:
                $type = "template";
                $template = config('site.wa_reminder');
                $image = config("site.assets")."/img/guide/dharma01.jpeg";
                $var1 = $contact->name;
                $var2 = "The Bali Night Walking and Food Tour will start tonight at *05.00PM* and our meeting point is in front of *Lapangan Puputan Badung* (Look for sign 0KM)";
                $var3 = "His name is *Dharma*. He will be the tour guide on duty and will be waiting for you at meeting point 😊";
                $var4 = "See you 🙏😊";
            break;

            case 11:
                $type = "text";
                $var1 = "Got it 🫡 Thank you for your confirmation 🙏😊";
            break;

            case 51:
                //Bali Food Tour
                $type = "template_2";
                $template = config('site.wa_review');
                $var1 = $contact->name;
                $var2 = "Thank you for visiting Bali and joining our tour. Hope it give you a good memory about Bali.";
                $var3 = "https://www.tripadvisor.com/UserReviewEdit-g297694-d27418484.html";
            break;

            case 52:
                //Bali Village Tour
                $type = "template_2";
                $template = config('site.wa_review');
                $var1 = $contact->name;
                $var2 = "Thank you for visiting Bali and joining our tour. Hope it give you a good memory about Bali.";
                $var3 = "https://www.tripadvisor.com/UserReviewEdit-g1025508-d16807840.html";
            break;

            case 55:
                //Ubud Food Tour
                $type = "template_2";
                $template = config('site.wa_review');
                $var1 = $contact->name;
                $var2 = "Thank you for visiting Bali and joining our tour. Hope it give you a good memory about Bali.";
                $var3 = "https://www.tripadvisor.com/UserReviewEdit-g294226-d27735579.html";
            break;

            case 53:
                //Jogja Morning Tour
                $type = "template_2";
                $template = config('site.wa_review');
                $var1 = $contact->name;
                $var2 = "Thank you for visiting Yogyakarta and joining our tour. Hope it give you a good memory about Yogyakarta.";
                $var3 = "https://www.tripadvisor.com/UserReviewEdit-g14782503-d25070180.html";
            break;

            case 54:
                //Jogja Night Food Tour
                $type = "template_2";
                $template = config('site.wa_review');
                $var1 = $contact->name;
                $var2 = "Thank you for visiting Yogyakarta and joining our tour. Hope it give you a good memory about Yogyakarta.";
                $var3 = "https://www.tripadvisor.com/UserReviewEdit-g14782503-d15646790.html";
            break;
            
        }

        if($type=="template_2")
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
                                            ]
                                        ]
                                    ]
                              ];
            
            $whatsapp = new WhatsappHelper;
            $whatsapp->sendTemplate($contact->wa_id,$template, $components);
        }

        if($type=="template")
        {
            if($var1=="") $var1="friend";
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
