<?php
namespace budisteikul\tourcms\Helpers;
use Illuminate\Http\Request;

use budisteikul\toursdk\Helpers\BokunHelper;
use budisteikul\toursdk\Helpers\ImageHelper;
use budisteikul\toursdk\Helpers\ProductHelper;
use budisteikul\toursdk\Helpers\PaypalHelper;
use budisteikul\toursdk\Helpers\MidtransHelper;
use budisteikul\toursdk\Models\Product;
use budisteikul\toursdk\Models\Shoppingcart;
use budisteikul\toursdk\Models\ShoppingcartProduct;
use budisteikul\toursdk\Models\ShoppingcartProductDetail;
use budisteikul\toursdk\Models\ShoppingcartQuestion;
use budisteikul\toursdk\Models\ShoppingcartQuestionOption;
use budisteikul\toursdk\Models\ShoppingcartPayment;
use Illuminate\Support\Facades\Mail;
use budisteikul\toursdk\Mail\BookingConfirmedMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class BookingHelper {
	
	
	
	public static function insert_shoppingcart($contents,$id)
	{
		$shoppingcart = Shoppingcart::where('booking_status','CART')->where('session_id',$id)->delete();
		
		$activity = $contents->activityBookings;
		$shoppingcart = new Shoppingcart();
		$shoppingcart->session_id = $id;
		$shoppingcart->booking_channel = 'WEBSITE';
		$shoppingcart->confirmation_code = self::get_ticket();
		$shoppingcart->currency = $contents->customerInvoice->currency;
		if(isset($contents->promoCode)) $shoppingcart->promo_code = $contents->promoCode->code;
		$shoppingcart->save();
		
		$grand_total = 0;
		$grand_subtotal = 0;
		$grand_discount = 0;
		$grand_due_now = 0;
		$grand_due_on_arrival = 0;
		for($i=0;$i<count($activity);$i++)
		{

			$product = Product::where('bokun_id',$activity[$i]->activity->id)->firstOrFail();

			$product_invoice = $contents->customerInvoice->productInvoices;
			$lineitems = $product_invoice[$i]->lineItems;
			
			$shoppingcart_product = new ShoppingcartProduct();
			
			$shoppingcart_product->shoppingcart_id = $shoppingcart->id;
			$shoppingcart_product->product_confirmation_code = $activity[$i]->productConfirmationCode;
			$shoppingcart_product->booking_id = $activity[$i]->id;
			$shoppingcart_product->product_id = $activity[$i]->activity->id;
			if(isset($product_invoice[$i]->product->keyPhoto->derived[2]->url))
			{
				$shoppingcart_product->image = $product_invoice[$i]->product->keyPhoto->derived[2]->url;
			}
			else
			{
				$shoppingcart_product->image = ImageHelper::thumbnail($product);
			}
					
			$shoppingcart_product->title = $activity[$i]->activity->title;
			$shoppingcart_product->rate = $activity[$i]->rate->title;
			$shoppingcart_product->currency = $contents->customerInvoice->currency;
			$shoppingcart_product->date = ProductHelper::texttodate($product_invoice[$i]->dates);
			$shoppingcart_product->save();
			
			$subtotal_product = 0;
			$total_discount = 0;
			$total_product = 0;

			for($z=0;$z<count($lineitems);$z++)
			{
					$itemBookingId = $lineitems[$z]->itemBookingId;
					$itemBookingId = explode("_",$itemBookingId);
					
					$type_product = 'product';
					if($lineitems[$z]->people==0)
					{
						$type_product = "extra";
					}
					if($itemBookingId[1]=="pickup"){
						$type_product = "pickup";
					}


					$unitPrice = 'Price per booking';
					if($lineitems[$z]->title!="Passengers")
					{
						$unitPrice = $lineitems[$z]->title;
					}
					
					
					if($itemBookingId[1]=="pickup"){
						$type_product = "pickup";
					}
					
					
					
					if($type_product=="product")
					{
						
						$shoppingcart_product_detail = new ShoppingcartProductDetail();
						
						$shoppingcart_product_detail->shoppingcart_product_id = $shoppingcart_product->id;
						$shoppingcart_product_detail->type = $type_product;
						
						$shoppingcart_product_detail->title = $activity[$i]->activity->title;
						$shoppingcart_product_detail->people = $lineitems[$z]->people;
						$shoppingcart_product_detail->qty = $lineitems[$z]->quantity;
						$shoppingcart_product_detail->price = $lineitems[$z]->unitPrice;
						$shoppingcart_product_detail->unit_price = $unitPrice;
						$subtotal = $lineitems[$z]->unitPrice * $shoppingcart_product_detail->qty;
						$discount = $subtotal - ($lineitems[$z]->discountedUnitPrice * $shoppingcart_product_detail->qty);
						$total = $subtotal - $discount;
						$shoppingcart_product_detail->discount = $discount;
						$shoppingcart_product_detail->subtotal = $subtotal;
						$shoppingcart_product_detail->currency = $contents->customerInvoice->currency;
						$shoppingcart_product_detail->total = $total;
						$shoppingcart_product_detail->save();

						$subtotal_product += $subtotal;
						$total_discount += $discount;
						$total_product += $total;
					}

					if($type_product=="extra")
					{
						
						$shoppingcart_product_detail = new ShoppingcartProductDetail();
						
						$shoppingcart_product_detail->shoppingcart_product_id = $shoppingcart_product->id;
						$shoppingcart_product_detail->type = $type_product;
						
						$shoppingcart_product_detail->title = $activity[$i]->activity->title;
						$shoppingcart_product_detail->people = $lineitems[$z]->people;
						$shoppingcart_product_detail->qty = $lineitems[$z]->quantity;
						$shoppingcart_product_detail->price = $lineitems[$z]->unitPrice;
						$shoppingcart_product_detail->unit_price = $unitPrice;
						$subtotal = $lineitems[$z]->unitPrice * $shoppingcart_product_detail->qty;
						$discount = $subtotal - ($lineitems[$z]->discountedUnitPrice * $shoppingcart_product_detail->qty);
						$total = $subtotal - $discount;
						$shoppingcart_product_detail->discount = $discount;
						$shoppingcart_product_detail->subtotal = $subtotal;
						$shoppingcart_product_detail->currency = $contents->customerInvoice->currency;
						$shoppingcart_product_detail->total = $total;
						$shoppingcart_product_detail->save();

						$subtotal_product += $subtotal;
						$total_discount += $discount;
						$total_product += $total;
					}
					
					if($type_product=="pickup")
					{
						$shoppingcart_product_detail = new ShoppingcartProductDetail();
						$shoppingcart_product_detail->shoppingcart_product_id = $shoppingcart_product->id;
						$shoppingcart_product_detail->type = $type_product;
						$shoppingcart_product_detail->title = 'Pick-up and drop-off services';
						$shoppingcart_product_detail->people = $lineitems[$z]->people;
						$shoppingcart_product_detail->qty = 1;
						$shoppingcart_product_detail->price = $lineitems[$z]->total;
						$shoppingcart_product_detail->unit_price = $unitPrice;
						$subtotal = $lineitems[$z]->total;
						$discount = $subtotal - $lineitems[$z]->discountedUnitPrice;
						$total = $subtotal - $discount;
						$shoppingcart_product_detail->discount = $discount;
						$shoppingcart_product_detail->subtotal = $subtotal;
						$shoppingcart_product_detail->total = $total;
						$shoppingcart_product_detail->currency = $contents->customerInvoice->currency;
						$shoppingcart_product_detail->save();
						
						$subtotal_product += $subtotal;
						$total_discount += $discount;
						$total_product += $total;
					}	
					
					
			}
			
			$shoppingcart_product->subtotal = $subtotal_product;
			$shoppingcart_product->discount = $total_discount;
			$shoppingcart_product->total = $total_product;

			$deposit = self::get_deposit($activity[$i]->activity->id,$total_product);
			$shoppingcart_product->due_now = $deposit->due_now;
			$shoppingcart_product->due_on_arrival = $deposit->due_on_arrival;
			$shoppingcart_product->save();
			
			$grand_discount += $total_discount;
			$grand_subtotal += $subtotal_product;
			$grand_total += $total_product;
			$grand_due_now += $deposit->due_now;
			$grand_due_on_arrival += $deposit->due_on_arrival;
		}
		
		$shoppingcart->subtotal = $grand_subtotal;
		$shoppingcart->discount = $grand_discount;
		$shoppingcart->total = $grand_total;
		$shoppingcart->due_now = $grand_due_now;
		$shoppingcart->due_on_arrival = $grand_due_on_arrival;
		$shoppingcart->save();

		
		
		// QUESTION ==============================================================================
		// Main Question ====
		$questions = BokunHelper::get_questionshoppingcart($id);


		$mainContactDetails = $questions->mainContactQuestions;
		$order = 1;
		foreach($mainContactDetails as $mainContactDetail)
		{
			
			$shoppingcart_question = new ShoppingcartQuestion();
			
			$shoppingcart_question->shoppingcart_id = $shoppingcart->id;
			$shoppingcart_question->type = 'mainContactDetails';
			$shoppingcart_question->question_id = $mainContactDetail->questionId;
			$shoppingcart_question->label = $mainContactDetail->label;
			$shoppingcart_question->data_type = $mainContactDetail->dataType;
			if(isset($mainContactDetail->dataFormat)) $shoppingcart_question->data_format = $mainContactDetail->dataFormat;
			$shoppingcart_question->required = $mainContactDetail->required;
			$shoppingcart_question->select_option = $mainContactDetail->selectFromOptions;
			$shoppingcart_question->select_multiple = $mainContactDetail->selectMultiple;
			$shoppingcart_question->order = $order;
			$shoppingcart_question->save();
			$order += 1;
			
			if($mainContactDetail->selectFromOptions=="true")
			{
				$order_option = 1;
				foreach($mainContactDetail->answerOptions as $answerOption)
				{
					
					$shoppingcart_question_option = new ShoppingcartQuestionOption();
					
					$shoppingcart_question_option->shoppingcart_questions_id = $shoppingcart_question->id;
					$shoppingcart_question_option->label = $answerOption->label;
					$shoppingcart_question_option->value = $answerOption->value;
					$shoppingcart_question_option->order = $order_option;
					$shoppingcart_question_option->save();
					$order_option += 1;
				}
			}
		}
		
		
		//===========================================================================
		$order = 1;
		for($ii = 0; $ii < count($questions->checkoutOptions); $ii++){
			// Pickup Question
			if(isset($questions->checkoutOptions[$ii]->pickup->questions)){
					$activityBookingId = $questions->checkoutOptions[$ii]->activityBookingDetail->activityBookingId;
					$pickupQuestion = $questions->checkoutOptions[$ii]->pickup->questions[0];

					$shoppingcart_question = new ShoppingcartQuestion();
					$shoppingcart_question->shoppingcart_id = $shoppingcart->id;
					$shoppingcart_question->type = 'pickupQuestions';
					$shoppingcart_question->booking_id = $activityBookingId;
					$shoppingcart_question->question_id = $pickupQuestion->questionId;
					$shoppingcart_question->label = $pickupQuestion->label;
					$shoppingcart_question->data_type = $pickupQuestion->dataType;
					$shoppingcart_question->required = $pickupQuestion->required;
					$shoppingcart_question->select_option = $pickupQuestion->selectFromOptions;
					$shoppingcart_question->select_multiple = $pickupQuestion->selectMultiple;
					$shoppingcart_question->order = $order;
					$shoppingcart_question->save();
					$order += 1;
			}
			// ActivityBookings question
			if(isset($questions->checkoutOptions[$ii]->perBookingQuestions)){
				$activityBookingId = $questions->checkoutOptions[$ii]->activityBookingDetail->activityBookingId;
				for($jj = 0; $jj < count($questions->checkoutOptions[$ii]->perBookingQuestions); $jj++)
				{
					$activityBookingQuestion = $questions->checkoutOptions[$ii]->perBookingQuestions[$jj];
					$shoppingcart_question = new ShoppingcartQuestion();
					
					$shoppingcart_question->shoppingcart_id = $shoppingcart->id;
					$shoppingcart_question->type = 'activityBookings';
					$shoppingcart_question->booking_id = $activityBookingId;
					$shoppingcart_question->question_id = $activityBookingQuestion->questionId;
					$shoppingcart_question->label = $activityBookingQuestion->label;
					$shoppingcart_question->data_type = $activityBookingQuestion->dataType;
					if(isset($activityBookingQuestion->dataFormat)) $shoppingcart_question->data_format = $activityBookingQuestion->dataFormat;
					if(isset($activityBookingQuestion->help)) $shoppingcart_question->help = $activityBookingQuestion->help;
					$shoppingcart_question->required = $activityBookingQuestion->required;
					$shoppingcart_question->select_option = $activityBookingQuestion->selectFromOptions;
					$shoppingcart_question->select_multiple = $activityBookingQuestion->selectMultiple;
					$shoppingcart_question->order = $order;
					$shoppingcart_question->save();
					$order += 1;

					if($activityBookingQuestion->selectFromOptions=="true")
					{
						$order_option = 1;
						foreach($activityBookingQuestion->answerOptions as $answerOption)
						{
							
							$shoppingcart_question_option = new ShoppingcartQuestionOption();
							
							$shoppingcart_question_option->shoppingcart_questions_id = $shoppingcart_question->id;
							$shoppingcart_question_option->label = $answerOption->label;
							$shoppingcart_question_option->value = $answerOption->value;
							$shoppingcart_question_option->order = $order_option;
							$shoppingcart_question_option->save();
							$order_option += 1;
						}
					}
				}
			}
		}
		//===========================================================================
	}
	


	public static function update_shoppingcart($contents,$id)
	{
		$activity = $contents->activityBookings;
		$shoppingcart = Shoppingcart::where('booking_status','CART')->where('session_id',$id)->first();
		$shoppingcart->session_id = $id;
		$shoppingcart->currency = $contents->customerInvoice->currency;
		if(isset($contents->promoCode))
		{
			$shoppingcart->promo_code = $contents->promoCode->code;
		}
		else
		{
			$shoppingcart->promo_code = null;
		}
		$shoppingcart->save();
		
		$shoppingcart->shoppingcart_products()->delete();

		$grand_total = 0;
		$grand_subtotal = 0;
		$grand_discount = 0;
		$grand_due_now = 0;
		$grand_due_on_arrival = 0;
		for($i=0;$i<count($activity);$i++)
		{
			$product = Product::where('bokun_id',$activity[$i]->activity->id)->firstOrFail();

			$product_invoice = $contents->customerInvoice->productInvoices;
			$lineitems = $product_invoice[$i]->lineItems;
			
			$shoppingcart_product = new ShoppingcartProduct();
			$shoppingcart_product->shoppingcart_id = $shoppingcart->id;
			$shoppingcart_product->product_confirmation_code = $activity[$i]->productConfirmationCode;
			$shoppingcart_product->booking_id = $activity[$i]->id;
			$shoppingcart_product->product_id = $activity[$i]->activity->id;
			if(isset($product_invoice[$i]->product->keyPhoto->derived[2]->url))
			{
				$shoppingcart_product->image = $product_invoice[$i]->product->keyPhoto->derived[2]->url;
			}
			else
			{
				$shoppingcart_product->image = ImageHelper::thumbnail($product);
			}
			 
			$shoppingcart_product->title = $activity[$i]->activity->title;
			$shoppingcart_product->rate = $activity[$i]->rate->title;
			$shoppingcart_product->currency = $contents->customerInvoice->currency;
			$shoppingcart_product->date = ProductHelper::texttodate($product_invoice[$i]->dates);
			$shoppingcart_product->save();
			
			$subtotal_product = 0;
			$total_discount = 0;
			$total_product = 0;

			for($z=0;$z<count($lineitems);$z++)
			{
					$itemBookingId = $lineitems[$z]->itemBookingId;
					$itemBookingId = explode("_",$itemBookingId);
					
					$type_product = 'product';
					if($lineitems[$z]->people==0)
					{
						$type_product = 'extra';
					}
					if($itemBookingId[1]=="pickup"){
						$type_product = "pickup";
					}

					$unitPrice = 'Price per booking';
					if($lineitems[$z]->title!="Passengers")
					{
						$unitPrice = $lineitems[$z]->title;
					}

					if($type_product=="product")
					{
						
						$shoppingcart_product_detail = new ShoppingcartProductDetail();
						$shoppingcart_product_detail->shoppingcart_product_id = $shoppingcart_product->id;
						$shoppingcart_product_detail->type = $type_product;
						
						$shoppingcart_product_detail->title = $activity[$i]->activity->title;
						$shoppingcart_product_detail->people = $lineitems[$z]->people;
						$shoppingcart_product_detail->qty = $lineitems[$z]->quantity;
						$shoppingcart_product_detail->price = $lineitems[$z]->unitPrice;
						$shoppingcart_product_detail->unit_price = $unitPrice;
						$subtotal = $lineitems[$z]->unitPrice * $shoppingcart_product_detail->qty;
						$discount = $subtotal - ($lineitems[$z]->discountedUnitPrice * $shoppingcart_product_detail->qty);
						$total = $subtotal - $discount;
						$shoppingcart_product_detail->discount = $discount;
						$shoppingcart_product_detail->subtotal = $subtotal;
						$shoppingcart_product_detail->total = $total;
						$shoppingcart_product_detail->currency = $contents->customerInvoice->currency;
						$shoppingcart_product_detail->save();

						$subtotal_product += $subtotal;
						$total_discount += $discount;
						$total_product += $total;
					}

					if($type_product=="extra")
					{
						
						$shoppingcart_product_detail = new ShoppingcartProductDetail();
						$shoppingcart_product_detail->shoppingcart_product_id = $shoppingcart_product->id;
						$shoppingcart_product_detail->type = $type_product;
						
						$shoppingcart_product_detail->title = $activity[$i]->activity->title;
						$shoppingcart_product_detail->people = $lineitems[$z]->people;
						$shoppingcart_product_detail->qty = $lineitems[$z]->quantity;
						$shoppingcart_product_detail->price = $lineitems[$z]->unitPrice;
						$shoppingcart_product_detail->unit_price = $unitPrice;
						$subtotal = $lineitems[$z]->unitPrice * $shoppingcart_product_detail->qty;
						$discount = $subtotal - ($lineitems[$z]->discountedUnitPrice * $shoppingcart_product_detail->qty);
						$total = $subtotal - $discount;
						$shoppingcart_product_detail->discount = $discount;
						$shoppingcart_product_detail->subtotal = $subtotal;
						$shoppingcart_product_detail->total = $total;
						$shoppingcart_product_detail->currency = $contents->customerInvoice->currency;
						$shoppingcart_product_detail->save();

						$subtotal_product += $subtotal;
						$total_discount += $discount;
						$total_product += $total;
					}
					
					if($type_product=="pickup")
					{
						$shoppingcart_product_detail = new ShoppingcartProductDetail();
						$shoppingcart_product_detail->shoppingcart_product_id = $shoppingcart_product->id;
						$shoppingcart_product_detail->type = $type_product;
						$shoppingcart_product_detail->title = 'Pick-up and drop-off services';
						$shoppingcart_product_detail->people = $lineitems[$z]->people;
						$shoppingcart_product_detail->qty = 1;
						$shoppingcart_product_detail->price = $lineitems[$z]->total;
						$shoppingcart_product_detail->unit_price = $unitPrice;
						$subtotal = $lineitems[$z]->total;
						$discount = $subtotal - $lineitems[$z]->discountedUnitPrice;
						$total = $subtotal - $discount;
						$shoppingcart_product_detail->discount = $discount;
						$shoppingcart_product_detail->subtotal = $subtotal;
						$shoppingcart_product_detail->total = $total;
						$shoppingcart_product_detail->currency = $contents->customerInvoice->currency;
						$shoppingcart_product_detail->save();
						
						$subtotal_product += $subtotal;
						$total_discount += $discount;
						$total_product += $total;
					}	
					
			}
			
			
			
			
			$shoppingcart_product->subtotal = $subtotal_product;
			$shoppingcart_product->discount = $total_discount;
			$shoppingcart_product->total = $total_product;

			$deposit = self::get_deposit($activity[$i]->activity->id,$total_product);
			$shoppingcart_product->due_now = $deposit->due_now;
			$shoppingcart_product->due_on_arrival = $deposit->due_on_arrival;
			$shoppingcart_product->save();

			$grand_discount += $total_discount;
			$grand_subtotal += $subtotal_product;
			$grand_total += $total_product;
			$grand_due_now += $deposit->due_now;
			$grand_due_on_arrival += $deposit->due_on_arrival;
		}
		
		

		$shoppingcart->subtotal = $grand_subtotal;
		$shoppingcart->discount = $grand_discount;
		$shoppingcart->total = $grand_total;
		$shoppingcart->due_now = $grand_due_now;
		$shoppingcart->due_on_arrival = $grand_due_on_arrival;
		$shoppingcart->save();
		//===============================================

		
		$questions = BokunHelper::get_questionshoppingcart($id);
		
		//===========================================================================
		$order = 1;
		for($ii = 0; $ii < count($questions->checkoutOptions); $ii++){
			// Pickup Question
			if(isset($questions->checkoutOptions[$ii]->pickup->questions)){
					$activityBookingId = $questions->checkoutOptions[$ii]->activityBookingDetail->activityBookingId;
					ShoppingcartQuestion::where('booking_id',$activityBookingId)->where('type','pickupQuestions')->delete();
					$pickupQuestion = $questions->checkoutOptions[$ii]->pickup->questions[0];

					$shoppingcart_question = new ShoppingcartQuestion();
					$shoppingcart_question->shoppingcart_id = $shoppingcart->id;
					$shoppingcart_question->type = 'pickupQuestions';
					$shoppingcart_question->booking_id = $activityBookingId;
					$shoppingcart_question->question_id = $pickupQuestion->questionId;
					$shoppingcart_question->label = $pickupQuestion->label;
					$shoppingcart_question->data_type = $pickupQuestion->dataType;
					$shoppingcart_question->required = $pickupQuestion->required;
					$shoppingcart_question->select_option = $pickupQuestion->selectFromOptions;
					$shoppingcart_question->select_multiple = $pickupQuestion->selectMultiple;
					$shoppingcart_question->order = $order;
					$shoppingcart_question->save();
					$order += 1;
			}
			// ActivityBookings question
			if(isset($questions->checkoutOptions[$ii]->perBookingQuestions)){
				$activityBookingId = $questions->checkoutOptions[$ii]->activityBookingDetail->activityBookingId;
				ShoppingcartQuestion::where('booking_id',$activityBookingId)->where('type','activityBookings')->delete();
				for($jj = 0; $jj < count($questions->checkoutOptions[$ii]->perBookingQuestions); $jj++)
				{
					$activityBookingQuestion = $questions->checkoutOptions[$ii]->perBookingQuestions[$jj];
					$shoppingcart_question = new ShoppingcartQuestion();
					
					$shoppingcart_question->shoppingcart_id = $shoppingcart->id;
					$shoppingcart_question->type = 'activityBookings';
					$shoppingcart_question->booking_id = $activityBookingId;
					$shoppingcart_question->question_id = $activityBookingQuestion->questionId;
					$shoppingcart_question->label = $activityBookingQuestion->label;
					$shoppingcart_question->data_type = $activityBookingQuestion->dataType;
					if(isset($activityBookingQuestion->dataFormat)) $shoppingcart_question->data_format = $activityBookingQuestion->dataFormat;
					if(isset($activityBookingQuestion->help)) $shoppingcart_question->help = $activityBookingQuestion->help;
					$shoppingcart_question->required = $activityBookingQuestion->required;
					$shoppingcart_question->select_option = $activityBookingQuestion->selectFromOptions;
					$shoppingcart_question->select_multiple = $activityBookingQuestion->selectMultiple;
					$shoppingcart_question->order = $order;
					$shoppingcart_question->save();
					$order += 1;

					if($activityBookingQuestion->selectFromOptions=="true")
					{
						$order_option = 1;
						foreach($activityBookingQuestion->answerOptions as $answerOption)
						{
							
							$shoppingcart_question_option = new ShoppingcartQuestionOption();
							
							$shoppingcart_question_option->shoppingcart_questions_id = $shoppingcart_question->id;
							$shoppingcart_question_option->label = $answerOption->label;
							$shoppingcart_question_option->value = $answerOption->value;
							$shoppingcart_question_option->order = $order_option;
							$shoppingcart_question_option->save();
							$order_option += 1;
						}
					}
				}
			}
		}
		//===========================================================================

		$booking_id = array();
		foreach($shoppingcart->shoppingcart_products()->get() as $product)
		{
			//$booking_id[] = array($product->booking_id);
			array_push($booking_id,$product->booking_id);
		}
		$shoppingcart->shoppingcart_questions()->whereNotIn('booking_id', $booking_id)->whereNotNull('booking_id')->delete();
		//===============================================
	}
	

	public static function get_deposit($bokunId,$amount)
	{
		$due_now = 0;
		$due_on_arrival = 0;
		$dataObj = new \stdClass();
		$product = Product::where('bokun_id',$bokunId)->first();
		if($product->deposit_amount==0)
		{
			$dataObj->due_now = $amount;
			$dataObj->due_on_arrival = 0;
		}
		else
		{
			if($product->deposit_percentage)
			{
				
				$dataObj->due_now = $amount * $product->deposit_amount / 100;
				$dataObj->due_on_arrival = $amount - $dataObj->due_now;
			}
			else
			{
				$dataObj->due_now = $product->deposit_amount;
				$dataObj->due_on_arrival = $amount - $dataObj->due_now;
			}
		}
			
		return $dataObj;
	}


	public static function get_shoppingcart($id,$action="insert",$contents)
	{
		if($action=="insert")
			{
				self::insert_shoppingcart($contents,$id);
			}
		if($action=="update")
			{
				self::update_shoppingcart($contents,$id);
			}
	}
	
	

	public static function shoppingcart_clear($shoppingcart)
	{
		BokunHelper::get_removepromocode($shoppingcart->session_id);
		foreach($shoppingcart->shoppingcart_products()->get() as $shoppingcart_product)
            {
                BokunHelper::get_removeactivity($shoppingcart->session_id,$shoppingcart_product->booking_id);
            }
        //Session::forget('sessionId');
        return $shoppingcart;
	}

	

	

	public static function save_question($shoppingcart,$request)
	{
		foreach($shoppingcart->shoppingcart_questions()->get() as $question)
            {
                $shoppingcart_question = ShoppingcartQuestion::find($question->id);
                $shoppingcart_question->answer = $request->input($question->id);
                $shoppingcart_question->save();
                
                if($shoppingcart_question->select_option)
                {
                    $shoppingcart_question_options = ShoppingcartQuestionOption::where('shoppingcart_questions_id',$shoppingcart_question->id)->get();
                    foreach($shoppingcart_question_options as $shoppingcart_question_option)
                    {
                        if($shoppingcart_question_option->value==$request->input($question->id))
                        {
                            $shoppingcart_question_option = ShoppingcartQuestionOption::find($shoppingcart_question_option->id);
                            $shoppingcart_question_option->answer = 1;
                            $shoppingcart_question_option->save();
                        }
                        else
                        {
                            $shoppingcart_question_option_ = ShoppingcartQuestionOption::find($shoppingcart_question_option->id);
                            $shoppingcart_question_option_->answer = 0;
                            $shoppingcart_question_option_->save();
                        }
                        
                    }
                }
            }
        return $shoppingcart;
	}

	public static function create_payment($shoppingcart)
	{
		$shoppingcart->confirmation_code = self::get_ticket();
		$shoppingcart->save();
		
			ShoppingcartPayment::updateOrCreate(
				['shoppingcart_id' => $shoppingcart->id],
				[
					'payment_provider' => 'none',
					'amount' => $shoppingcart->due_now,
					'currency' => "IDR",
					'payment_status' => 2
				]
			);
			$response = "";
		
		return $response;
	}


	public static function remove_promocode($shoppingcart)
	{
		$contents = BokunHelper::get_removepromocode($shoppingcart->session_id);
        self::get_shoppingcart($shoppingcart->session_id,"update",$contents);
        return $shoppingcart;
	}

	public static function remove_activity($shoppingcart,$bookingId)
	{
		ShoppingcartQuestion::where('booking_id',$bookingId)->delete();
		$contents = BokunHelper::get_removeactivity($shoppingcart->session_id,$bookingId);
		self::get_shoppingcart($shoppingcart->session_id,"update",$contents);
		return $shoppingcart;
	}

	public static function apply_promocode($shoppingcart,$promocode)
	{
		$status = false;
		$contents = BokunHelper::get_applypromocode($shoppingcart->session_id,$promocode);
		
		if(!isset($contents->message))
		{
			$status = true;
			self::get_shoppingcart($shoppingcart->session_id,"update",$contents);
		}
		return $status;
	}

	public static function get_ticket(){
    	$uuid = "VER-". rand(100000,999999);
    	while( Shoppingcart::where('confirmation_code','=',$uuid)->first() ){
        	$uuid = "VER-". rand(100000,999999);
    	}
    	return $uuid;
	}
	
	
}
?>
