<?php

namespace App\Library;

use App\Models\OrderbookTable;

use App\Library\Common;

class Orderbook {

    public static function getOrderBookList( $customer_id, $want_asset, $offer_asset, $side ) {
        $orderbookTable = new OrderbookTable();
        $orderbookTable->setCollection($want_asset, $offer_asset);
        return $orderbookTable->getOrderBookList($customer_id, $want_asset, $offer_asset, $side);
    }

    public static function getBestPrice( $customer_id, $want_asset, $offer_asset, $side ) {
        $orderbookTable = new OrderbookTable();
        $orderbookTable->setCollection($want_asset, $offer_asset);
        return $orderbookTable->getBestPrice($customer_id, $want_asset, $offer_asset, $side);
    }
    public static function addNewOrderBook($orderInst) {
        $orderbookTable = new OrderbookTable();
        $orderbookTable->setCollection($orderInst['want_asset'], $orderInst['offer_asset']);

        $orderbookTable->customer_id = $orderInst['customer_id'];
        $orderbookTable->want_asset = $orderInst['want_asset'];
        $orderbookTable->offer_asset = $orderInst['offer_asset'];
        $orderbookTable->quantity = $orderInst['quantity'];
        $orderbookTable->side = $orderInst['side'];
        $orderbookTable->type = $orderInst['type'];
        $orderbookTable->limit_price = $orderInst['limit_price'];
        $orderbookTable->time_in_force = $orderInst['time_in_force'];
        $orderbookTable->remaining_quantity = $orderInst['remaining_quantity'];
        $orderbookTable->filled_quantity = $orderInst['filled_quantity'];
        $orderbookTable->created_at = Common::current_utc_date();
        $orderbookTable->updated_at = Common::current_utc_date();
        $orderbookTable->save();
    }
    public static function getOrderOfUser($customer_id, $want_asset, $offer_asset) {
        $orderbookTable = new OrderbookTable();
        $orderbookTable->setCollection($want_asset, $offer_asset);
        $data = $orderbookTable->where('customer_id', $customer_id)->where('want_asset', $want_asset)->where('offer_asset', $offer_asset)->get()->toArray();
//        echo $data;exit;
        $ret_arr = array();
        if ( count($data) > 0 ) {
            foreach( $data as $d ) {
                $rep = Common::OrderToArray($d);
                $rep['created_at'] = Common::DateFromBSON($d['created_at']);
                $rep['updated_at'] = Common::DateFromBSON($d['updated_at']);
                $ret_arr[] = $rep;
            }
        }
        return $ret_arr;
    }
    public static function updateOrder( $want_asset, $offer_asset, $id, $update_arr ) {
        $orderbookTable = new OrderbookTable();
        $orderbookTable->setCollection($want_asset, $offer_asset);
        $orderbookTable->updatedOrder($id, $update_arr);
    }
}