<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class OrderController extends Controller
{
    //Get all orders from DB
    public function getOrder() {


        $order = DB::table('orders')

            ->select(  'orders.id_order', 'orders.reference','orders.date_add', 'customer.firstname',
                'customer.lastname', 'address.address1', 'address.address2', 'address.city', 'address.country_name',
                 'order_detail.product_name', 'order_detail.product_quantity', 'order_state_lang.name')
            ->joinSub($this->getAddress(),'address', function($join) {
                $join->on('address.id_address', '=', 'orders.id_address_delivery');
            })
            ->join('customer', 'customer.id_customer','orders.id_customer')
            ->join('order_state_lang', 'order_state_lang.id_order_state','orders.current_state')
            ->join('order_detail', 'order_detail.id_order','orders.id_order')

            ->get();

        return response()->json(['order' => $order], 200);
    }

    public function getAddress()
    {

        return DB::table('address')
            ->select('address.id_address', 'country_lang.country_name', 'address.address1', 'address.address2', 'address.city')
            ->join('country_lang', 'country_lang.id_country','address.id_country');


    }

    //Modify Current State
    public function updateState(Request $request, $id_order) {

        DB::table('orders')
            ->where([
                'id_order' => $id_order
            ])
            ->update([
                'current_state' => $request->current_state
            ]);

        $response = DB::table('orders')
            ->where([
                'id_order' => $id_order
            ])
            ->get();



        return response()->json(["order" => $response], 200);
    }

}
