<?php
namespace budisteikul\tourcms\Helpers;
use budisteikul\tourcms\Models\ShoppingcartProduct;
use budisteikul\tourcms\Models\Shoppingcart;

class ReportHelper {

    public static function traveler_booking_per_month($month,$year)
    {
        
        $bookings = Shoppingcart::with('shoppingcart_products')->WhereHas('shoppingcart_products', function($query) use ($month,$year) {
                 $query->whereYear('date',$year)->whereMonth('date',$month);
            })->where('booking_status','CONFIRMED')->groupBy('booking_channel')->select('booking_channel')->get();

        foreach($bookings as $booking)
        {
            $total = 0;
            $booking_channel = $booking->booking_channel;
            $products = ShoppingcartProduct::with('shoppingcart')
            ->WhereHas('shoppingcart', function($query) use ($booking_channel) {
                 $query->where('booking_status','CONFIRMED')->where('booking_channel',$booking_channel);
            })->whereMonth('date',$month)->whereYear('date',$year)->get();
        
            foreach($products as $product)
            {
                foreach($product->shoppingcart_product_details as $shoppingcart_product_detail)
                {
                    $people = $shoppingcart_product_detail->people;
                    $total += $people;
                }
            }

            $value[] = [
                "booking_channel" => $booking_channel,
                "total" => $total
            ];
        }
            

        return $value;
    }

    public static function traveller_product_per_year($title,$year)
    {
        $total = 0;
        $products = ShoppingcartProduct::with('shoppingcart')
        ->WhereHas('shoppingcart', function($query) {
                 $query->where('booking_status','CONFIRMED');
            })->where('title',$title)->whereYear('date',$year)->whereMonth('date',$month)->get();
        
        foreach($products as $product)
        {
            foreach($product->shoppingcart_product_details as $shoppingcart_product_detail)
            {
                $people = $shoppingcart_product_detail->people;
                $total += $people;
            }
        }
        return $total;
    }

    public static function traveller_per_day($day,$month,$year)
    {
        $total = 0;
        $products = ShoppingcartProduct::with('shoppingcart')
        ->WhereHas('shoppingcart', function($query) {
                 $query->where('booking_status','CONFIRMED');
            })->whereYear('date',$year)->whereMonth('date',$month)->whereDay('date',$day)->get();
        
        foreach($products as $product)
        {
            foreach($product->shoppingcart_product_details as $shoppingcart_product_detail)
            {
                $people = $shoppingcart_product_detail->people;
                $total += $people;
            }
        }
        return $total;
    }

    public static function traveller_per_month($month,$year)
    {
        $total = 0;
        $products = ShoppingcartProduct::with('shoppingcart')
        ->WhereHas('shoppingcart', function($query) {
                 $query->where('booking_status','CONFIRMED');
            })->whereMonth('date',$month)->whereDay('date',$day)->get();
        
        foreach($products as $product)
        {
            foreach($product->shoppingcart_product_details as $shoppingcart_product_detail)
            {
                $people = $shoppingcart_product_detail->people;
                $total += $people;
            }
        }
        return $total;
    }

    public static function traveller_product_per_month($title,$month,$year)
    {
        $total = 0;
        $products = ShoppingcartProduct::with('shoppingcart')
        ->WhereHas('shoppingcart', function($query) {
                 $query->where('booking_status','CONFIRMED');
            })->where('title',$title)->whereYear('date',$year)->whereMonth('date',$month)->get();
        
        foreach($products as $product)
        {
            foreach($product->shoppingcart_product_details as $shoppingcart_product_detail)
            {
                $people = $shoppingcart_product_detail->people;
                $total += $people;
            }
        }
        return $total;
    }

}
?>