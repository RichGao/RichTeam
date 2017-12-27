<?php

namespace App\Models;

use App\Models\BaseModel;

class OrderbookTable extends BaseModel
{
    //
    protected $collection = 'orderbook';

    public function setCollection( $want_asset, $offer_asset ) {
        $this->collection .= '_'.$want_asset.'_'.$offer_asset;
    }

    public function getOrderBookList( $customer_id, $want_asset, $offer_asset, $side ) {
        $order_direct = array('buy'=>'asc', 'sell'=>'desc');
        $data = $this->where('side', '<>', $side)
//                     ->where('customer_id', '<>', $customer_id)
                     ->where('want_asset', $want_asset)
                     ->where('offer_asset', $offer_asset)
                     ->orderBy('limit_price', $order_direct[$side])
                     ->orderBy('created_at', 'asc')
                     ->get()
                     ->toArray();
        return $data;
    }
    public function getBestPrice( $customer_id, $want_asset, $offer_asset, $side ) {
        $order_direct = array('buy'=>'asc', 'sell'=>'desc');
        $data = $this->where('side', '<>', $side)
//            ->where('customer_id', '<>', $customer_id)
            ->where('want_asset', $want_asset)
            ->where('offer_asset', $offer_asset)
            ->orderBy('limit_price', $order_direct[$side])
            ->orderBy('created_at', 'asc')
            ->first();
        if ( is_null($data) ) return 0;
        return $data['limit_price'];
    }
    public function updatedOrder($id, $update_arr) {
        $this->where('_id', $id)->update($update_arr);
    }
}
