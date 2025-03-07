<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JeroenDesloovere\VCard\VCard;
use budisteikul\tourcms\Models\Shoppingcart;
use budisteikul\tourcms\Helpers\BookingHelper;
use budisteikul\tourcms\Helpers\GeneralHelper;
use Illuminate\Support\Str;

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
        
        //return $vCard->download();
        $vCard = $vCard->getOutput();
        $filename = $contact->firstName .'-'. $contact->lastName .'-'. date('ymd') .'.vcf';
        $filename = Str::slug($filename,'-');
        return response($vCard)
            ->header('Content-Type', 'text/x-vcard')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"')
            ->header('Content-Length', mb_strlen($vCard, 'utf-8'));   
    }

    
}
