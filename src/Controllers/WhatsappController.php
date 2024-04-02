<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;
use budisteikul\toursdk\Models\WAContact;
use budisteikul\toursdk\Models\WAMessage;
use Illuminate\Http\Request;

class WhatsappController extends Controller
{
    
    public function chat($id)
    {
        $contact = WAContact::where('id',$id)->firstOrFail();
        $messages = WAMessage::where('contact_id',$contact->id)->orderBy('created_at','asc')->get();
        return view('tourcms::whatsapp.chat',['contact'=>$contact,'messages'=>$messages]);
    }

}
