<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JeroenDesloovere\VCard\VCard;
use budisteikul\toursdk\Models\Shoppingcart;
use budisteikul\toursdk\Helpers\BookingHelper;
use budisteikul\toursdk\Helpers\GeneralHelper;

class VCardController extends Controller
{
    public function index($id)
    {
        $shoppingcart = Shoppingcart::findOrFail($id);
        $contact = BookingHelper::get_answer_contact($shoppingcart);

        $contact->phoneNumber = GeneralHelper::phoneNumber($contact->phoneNumber,"+");

        $vCard = new VCard();
        $vCard->addName(date('ymd'), $contact->firstName .' '. $contact->lastName);
        $vCard->addPhoneNumber($contact->phoneNumber);
        // Add more information as needed
 
        return $vCard->download();
        
    }

    
}
