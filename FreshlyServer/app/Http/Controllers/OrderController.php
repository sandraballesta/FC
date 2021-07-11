<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    //Get all orders from DB
    public function getOrder(){

        $order = DB::table('orders')
            ->select('orders.reference','orders.date_add', 'customer.firstname',
                'customer.lastname', 'address.address1', 'address.address2', 'address.city',
                'country_lang.name', 'order_detail.product_name', 'order_detail.product_quantity', 'order_state_lang.name')
            ->join('address', 'address.id_address', 'orders.id_address_delivery')
            ->join('country_lang', 'country_lang.id_country','address.id_country')
            ->join('customer', 'customer.id_customer','orders.id_customer')
            ->join('order_state_lang', 'order_state_lang.id_order_state','orders.current_state')
            ->join('order_detail', 'order_detail.id_order','orders.id_order')
            ->get();

        return response()->json(['order' => $order], 200);
    }
}
