<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use budisteikul\toursdk\Models\WAContact;
use budisteikul\toursdk\Models\WAMessage;
use Illuminate\Http\Request;

class WhatsappController extends Controller
{
    public function index()
    {
        print_r("halooo");
    }

    public function show(WAContact $contact)
    {

        
    }
    
    public function create()
    {
        
    }

    
    public function store(Request $request)
    {
        
    }
    
    public function edit(WAContact $contact)
    {
        print_r($contact->wa_id);
        //$contact = WAContact::where('id',$id)->firstOrFail();
        //$messages = WAMessage::where('contact_id',$contact->id)->orderBy('created_at','asc')->get();
        //return view('tourcms::whatsapp.edit',['contact'=>$contact,'messages'=>$messages]);
    }

    
    public function update(Request $request, WAContact $contact)
    {
        
    }

   
    public function destroy(WAContact $contact)
    {
        
    }

    

}
