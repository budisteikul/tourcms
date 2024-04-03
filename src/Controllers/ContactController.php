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

class ContactController extends Controller
{
    public function message(Request $request)
    {
        $id = $request->input('id');
        $contact = Contact::where('id',$id)->firstOrFail();
        $messages = Message::where('contact_id',$contact->id)->orderBy('created_at','desc')->get();

        $output = '';
        foreach($messages as $message)
        {
            $style1 = 'card bg-light mb-2';
            if($message->from==null)
            {
                $style1 = 'card text-white bg-success mb-2';
            }

            $message_text = '';
            if($message->type=="text")
            {
                $message_text = json_decode($message->text)->body;
            }

            if($message->type=="image")
            {
                $image = json_decode($message->image);
                $image_link = '';
                if(isset($image->link)) $image_link = $image->link;
                $image_text = '<img src="'.$image_link.'" class="img-thumbnail mb-2" style="max-height: 100px;">';
                $message_text = $image_text;
                if(isset($image->caption)) $message_text = $image_text.'<br />'. $image->caption;
            }

            if($message->type=="reaction")
            {
                $message_text = json_decode($reaction->text)->emoji;
            }

            if($message->type=="template")
            {
                $message_text = json_decode($message->template)->name;
            }

            $output .= '<div class="'.$style1.'" >
                            <div class="card-body">
                                <p class="card-text mb-0">'. nl2br($message_text) .'</p>
                                <small>'.GeneralHelper::dateFormat($message->created_at,2).'</small>
                            </div>
                        </div>';
        }

        return view('tourcms::contact.message',['output'=>$output]);
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

        $whatsapp = new WhatsappHelper;
        switch($template_id)
        {
            case 1:
                $whatsapp->sendText($contact->wa_id,"Hello ".$contact->name." 👋\nThank you for booking our tour 😊\nThe Yogyakarta Night Walking and Food Tours will start tonight at *6.45 PM* and our meeting point is arround *Tugu Jogja* (Yogyakarta Monument)\n\nMap\nhttps://linktr.ee/foodtour");
            break;
            case 2:
                $whatsapp->sendText($contact->wa_id,"Hello ".$contact->name." 👋\nThank you for booking our tour 😊\nThe Morning Food Tour in Yogyakarta will start tomorrow morning at *7.30 AM* and our meeting point is near *Lupis Mbah Satinem*\n\nMap\nhttps://linktr.ee/foodtour");
            break;
            case 3:
                $whatsapp->sendText($contact->wa_id,"By the way, do you have any food allergy or dietary requirements?");
            break;
            case 4:
                $whatsapp->sendText($contact->wa_id,"Got it 🫡 Thank you for your confirmation 🙏😊");
            break;
            case 5:
                $whatsapp->sendImage($contact->wa_id,"https://storage.googleapis.com/storage.vertikaltrip.com/assets/img/whatsapp/kalika.jpeg","Her name is Kalika Ratna. She will be the tour guide on duty and will be waiting for you at meeting point 😊");
            break;
            case 6:
                $whatsapp->sendImage($contact->wa_id,"https://storage.googleapis.com/storage.vertikaltrip.com/assets/img/whatsapp/anisa.jpeg","Her name is Anisa Rahma. She will be the tour guide on duty and will be waiting for you at meeting point 😊");
            break;
        }
        //$whatsapp->sendText("6285743112112","Thank you for booking our tour 😊\nThe Yogyakarta Night Walking and Food Tours will start tonight at *6.45 PM* and our meeting point is arround *Tugu Jogja* (Yogyakarta Monument)\n\nMap\nhttps://linktr.ee/foodtour");

        //$whatsapp->sendText("6285743112112","By the way, do you have any food allergy or dietary requirements?");
                
        //$whatsapp->sendImage("6285743112112","https://storage.googleapis.com/storage.vertikaltrip.com/assets/img/whatsapp/kalika.jpeg","Her name is Kalika Ratna. She will be the tour guide on duty and will be waiting for you at meeting point 😊");
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
