<?php

namespace App\Models;

use App\Library\Common;
use App\Models\BaseModel;
use DB;

class OrderTable extends BaseModel
{
    //
    protected $collection = 'order';



    public function setCollection( $want_asset, $offer_asset ) {
        $this->collection .= '_'.$want_asset.'_'.$offer_asset;
    }

    public function getOrderOfUser( $customer_id, $want_asset, $offer_asset ) {
        $data = $this->where('customer_id', $customer_id)->where('want_asset', $want_asset)->where('offer_asset', $offer_asset)->get()->toArray();
        return $data;
    }

    public function getOrderListOnMarketCondition( $side, $last_trade_price, $want_asset, $offer_asset ) {
        $limit_data = array();
        $stop_data = array();
        $stoplimit_data = array();
        if ( $side == 'buy' ) {
            $limit_data = $this->where('side', $side)
                ->where('type', 'limit')
                ->where('status', 'pending')
                ->where('want_asset', $want_asset)
                ->where('offer_asset', $offer_asset)
                ->where('limit_price', '>=', $last_trade_price)->get()->toArray();
            $stop_data = $this->where('side', $side)
                ->where('type', 'limit')
                ->where('status', 'pending')
                ->where('want_asset', $want_asset)
                ->where('offer_asset', $offer_asset)
                ->where('stop_price', '<=', $last_trade_price)->get()->toArray();
            $stoplimit_data = $this->where('side', $side)
                ->where('type', 'limit')
                ->where('status', 'pending')
                ->where('want_asset', $want_asset)
                ->where('offer_asset', $offer_asset)
                ->where('limit_price', '>=', $last_trade_price)
                ->where('stop_price', '<=', $last_trade_price)->get()->toArray();
        }
        else {
            $limit_data = $this->where('side', $side)
                ->where('type', 'limit')
                ->where('status', 'pending')
                ->where('want_asset', $want_asset)
                ->where('offer_asset', $offer_asset)
                ->where('limit_price', '<=', $last_trade_price)->get()->toArray();
            $stop_data = $this->where('side', $side)
                ->where('type', 'limit')
                ->where('status', 'pending')
                ->where('want_asset', $want_asset)
                ->where('offer_asset', $offer_asset)
                ->where('stop_price', '>=', $last_trade_price)->get()->toArray();
            $stoplimit_data = $this->where('side', $side)
                ->where('type', 'limit')
                ->where('status', 'pending')
                ->where('want_asset', $want_asset)
                ->where('offer_asset', $offer_asset)
                ->where('limit_price', '<=', $last_trade_price)
                ->where('stop_price', '>=', $last_trade_price)->get()->toArray();
        }
        $order_data = array_merge($limit_data, $stop_data, $stoplimit_data);
        $new_order = array();
        if ( count($order_data) > 0 ) {
            $remove_order_ids = array();
            foreach( $order_data as $order ) {
                $remove_order_ids[] = $order['_id'];
                unset($order['_id']);
                unset($order['updated_at']);
                unset($order['created_at']);
                $order['created_at'] = Common::current_utc_date();
                $order['updated_at'] = Common::current_utc_date();
                $new_order[] = $order;
            }
            $this->whereIn('_id', $remove_order_ids)->delete();
        }
        else
            $new_order = null;
        return $new_order;
    }
}
